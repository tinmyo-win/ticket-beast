<?php

namespace Tests\SetUp;

use App\Models\Concert;
use App\Models\Order;
use App\Models\Ticket;

class OrderFactory
{
    public static function createForConcert($concert, $overrides = [], $ticketQuantity = 1)
    {
        $order = Order::factory()->create($overrides);
        $tickets = Ticket::factory($ticketQuantity)->create(['concert_id' => $concert->id]);
        $order->tickets()->saveMany($tickets);
        return $order;
    }
}
