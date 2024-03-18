<?php

namespace Tests\Feature;

use App\Models\Concert;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ViewOrderTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_view_their_order_confirmation()
    {
        $this->withoutExceptionHandling();

        $concert = Concert::factory()->create();
        $order = Order::factory()->create([
            'confirmation_number' => 'ORDERCONFIRMATION1234',
            'card_last_four' => 1881,
            'amount' => 8500
        ]);
        $ticketA = Ticket::factory()->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id,
            'code' => 'TICKETCODE123',
        ]);

        $ticketB = Ticket::factory()->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id,
            'code' => 'TICKETCODE2',
        ]);

        $response = $this->get('orders/ORDERCONFIRMATION1234');

        $response->assertStatus(200);

        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Orders/Show')
                ->has('order')
                ->where('order.email', $order->email)
                ->where('order.confirmation_number', $order->confirmation_number)
                ->where('order.card_last_four', '1881')
                ->has('order.tickets', 2)
                ->has('order.tickets.0', fn (AssertableInertia $page) => $page
                    ->where('code', 'TICKETCODE123')
                )
        );
    }
}
