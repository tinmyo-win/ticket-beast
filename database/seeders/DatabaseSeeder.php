<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Concert;
use App\Models\Order;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create([
            'name' => 'John',
            'email' => 'john@example.com',
        ]);

        $concert = Concert::factory()->published()->create([
            'user_id' => $user->id,
            'title' => 'The Red Chord',
            'subtitle' => 'with Lay Phyu and A Nge',
            'date' => Carbon::parse('March 13 2024 8:00 pm'),
            'ticket_price' => 600000,
            'venue' => 'KanTawGyi',
            'venue_address' => '1234 Street',
            'city' => 'Yangon',
            'state' => 'YG',
            'zip'  => 33556,
            'additional_information' => 'For tickets, call 555 - (5555)',
            'poster_image_path' => 'posters/redchord.jpg'
        ])->addTickets(10);

        $order = Order::factory()->create([
            'confirmation_number' => 'order123',
        ]);

        Ticket::factory()->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id,
            'code' => 'DEJAVU123'
        ]);

        Concert::factory(10)->create();
    }
}
