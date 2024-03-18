<?php

namespace Tests\Unit;

use App\Billing\FakePaymentGateway;
use App\Models\Concert;
use App\Models\Reservation;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function calculating_the_total_cost(): void
    {
        $tickets = collect([
            (object) ['price' => 1200],
            (object) ['price' => 1200],
            (object) ['price' => 1200],
        ]);

        $reservation = new Reservation($tickets, 'john@example.com');

        $this->assertEquals(3600, $reservation->totalCost());
    }

    /** @test */
    public function retreving_reservation_tickets()
    {
        $tickets = collect([
            (object) ['price' => 1200],
            (object) ['price' => 1200],
            (object) ['price' => 1200],
        ]);

        $reservation = new Reservation($tickets, 'john@example.com');

        $this->assertEquals($tickets, $reservation->tickets());
    }

    /** @test */
    public function retreving_customer_email()
    {
        $reservation = new Reservation(collect(), 'john@example.com');

        $this->assertEquals('john@example.com', $reservation->email());
    }

    /** @test */
    public function reserved_tickets_are_released_when_a_reservation_is_cancelled()
    {

        $tickets = collect([
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
        ]);

        $reservation = new Reservation($tickets, 'john@example.com');
        $reservation->cancel();

        foreach($tickets as $ticket)
        {
            $ticket->shouldHaveReceived('release');
        }
    }

    /** @test */
    public function completing_a_reservation()
    {
        $concert = Concert::factory()->create(['ticket_price' => 1200]);
        $tickets = Ticket::factory(3)->create(['concert_id' => $concert->id]);

        $reservation = new Reservation($tickets, 'john@example.com');

        $paymentGateway = new FakePaymentGateway;

        $order = $reservation->complete($paymentGateway, $paymentGateway->getValidTestToken(), 'test_acct_1234');

        $this->assertEquals('john@example.com', $order->email);
        $this->assertCount(3, $order->tickets);
        $this->assertEquals(3600, $order->amount);
        $this->assertEquals(3600, $paymentGateway->totalChargesFor('test_acct_1234'));
    }
}
