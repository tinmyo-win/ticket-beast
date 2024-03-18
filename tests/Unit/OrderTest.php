<?php

namespace Tests\Unit;

use App\Billing\Charge;
use App\Http\Resources\OrderResource;
use App\Models\Concert;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function converting_to_resource()
    {
        $order = Order::factory()->create([
            'confirmation_number' => 'ORDERCONFIRMATION123',
            'email' => 'john@example.com',
            'amount' => 6000,
            'card_last_four' => 4242
        ]);

        $concert = Concert::factory()->create();

        $order->tickets()->saveMany([
            Ticket::factory()->create(['concert_id' => $concert->id, 'code' => 'TICKETCODE1']),
            Ticket::factory()->create(['concert_id' => $concert->id, 'code' => 'TICKETCODE2']),
            Ticket::factory()->create(['concert_id' => $concert->id, 'code' => 'TICKETCODE3']),
        ]);

        $result = OrderResource::make($order)->toArray(new Request());

        $this->assertEquals('john@example.com', $result['email']);
        $this->assertEquals(6000, $result['amount']);
        $this->assertEquals('ORDERCONFIRMATION123', $result['confirmation_number']);
        $this->assertEquals(4242, $result['card_last_four']);
        $this->assertEquals([
            ['code' => 'TICKETCODE1'],
            ['code' => 'TICKETCODE2'],
            ['code' => 'TICKETCODE3'],
        ], $result['tickets']);

        $this->assertEquals($concert->id, $result['concert']->first()->id);
        $this->assertEquals($order->id, $result['id']);

        $this->assertCount(8, $result);
    }

    /** @test */
    public function creating_an_order_from_tickets_email_and_charge()
    {
        $charge = new Charge(['amount' => 3600, 'card_last_four' => '1234']);
        $tickets = collect([
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
        ]);

        $order = Order::forTickets($tickets, 'john@example.com', $charge);

        $this->assertEquals('john@example.com', $order->email);
        $this->assertEquals(3600, $order->amount);
        $this->assertEquals('1234', $order->card_last_four);

        $tickets->each->shouldHaveReceived('claimFor', [$order]);
    }

    /** @test */
    public function retreiving_an_order_by_confirmation_number()
    {
        $order = Order::factory()->create([
            'confirmation_number' => 'ORDERCONFIRMATION1234',
        ]);

        $foundOrder = Order::findByConfirmationNumber('ORDERCONFIRMATION1234');

        $this->assertEquals($order->id, $foundOrder->id);
    }

    /** @test */
    public function retreiving_a_nonexistent_order_by_confirmation_number_throws_an_exception()
    {
        $this->expectException(ModelNotFoundException::class);
        Order::findByConfirmationNumber('ORDERCONFIRMATION1234');

        // $this->fail('No matching order was found for the specified confirmation number, but an exception was not thrown');
    }
}
