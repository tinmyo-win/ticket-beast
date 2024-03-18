<?php

namespace Tests\Unit;

use App\Exceptions\NotEnoughTicketsException;
use App\Models\Concert;
use App\Models\Order;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConcertTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_get_formatted_date(): void
    {
        //Create a concert with known date
        $concert = Concert::factory()->make([
            'date' => Carbon::parse('2024-12-12 8:00pm')
        ]);

        //Retrieve the formatted date
        $date = $concert->formatted_date;

        //assert the formatted date
        $this->assertEquals('December 12, 2024', $date);
    }

    /** @test */
    public function can_get_formatted_start_time(): void
    {
        $concert = Concert::factory()->make([
            'date' => Carbon::parse('2024-12-12 17:00:00')
        ]);

        $this->assertEquals('5:00pm', $concert->formatted_start_time);
    }

    /** @test */
    public function can_get_ticket_price_in_dollars(): void
    {
        $concert = Concert::factory()->make([
            'ticket_price' => 350000,
        ]);

        $this->assertEquals('3,500.00', $concert->ticket_price_in_dollars);
    }

    /** @test */
    public function concert_with_a_published_at_date_are_published()
    {
        $publishedConcertA = Concert::factory()->published()->create();
        $publishedConcertB = Concert::factory()->published()->create();
        $unpublishedConcert = Concert::factory()->unpublished()->create();

        $publishedConcerts = Concert::published()->get();

        $this->assertTrue($publishedConcerts->contains($publishedConcertA));
        $this->assertTrue($publishedConcerts->contains($publishedConcertB));
        $this->assertFalse($publishedConcerts->contains($unpublishedConcert));
    }

    /** @test */
    public function concerts_can_be_published()
    {
        $concert = Concert::factory()->create([
            'published_at' => null,
            'ticket_quantity' => 5,
        ]);

        $this->assertFalse($concert->isPublished());
        $this->assertEquals(0, $concert->ticketsRemaining());

        $concert->publish();

        $this->assertTrue($concert->isPublished());
        $this->assertEquals(5, $concert->ticketsRemaining());
    }

    /** @test */
    public function can_add_tickets()
    {
        $concert = Concert::factory()->create()->addTickets(50);

        $this->assertEquals(50, $concert->ticketsRemaining());
    }

    /** @test */
    public function tickets_remaining_does_not_include_tickets_associated_with_an_order()
    {
        $concert = Concert::factory()->create();
        $concert->tickets()->saveMany(Ticket::factory(3)->create(['order_id' => 1]));
        $concert->tickets()->saveMany(Ticket::factory(2)->create(['order_id' => null]));

        $this->assertEquals(2, $concert->ticketsRemaining());
    }

    /** @test */
    public function tickets_sold_only_includes_tickets_associated_with_an_order()
    {
        $concert = Concert::factory()->create();
        $concert->tickets()->saveMany(Ticket::factory(3)->create(['order_id' => 1]));
        $concert->tickets()->saveMany(Ticket::factory(2)->create(['order_id' => null]));

        $this->assertEquals(3, $concert->ticketsSold());
    }

    /** @test */
    public function total_tickets_include_all_tickets()
    {
        $concert = Concert::factory()->create();
        $concert->tickets()->saveMany(Ticket::factory(3)->create(['order_id' => 1]));
        $concert->tickets()->saveMany(Ticket::factory(2)->create(['order_id' => null]));

        $this->assertEquals(5, $concert->totalTickets());
    }

    /** @test */
    public function calculating_the_revenue_in_dollars()
    {
        $this->withoutExceptionHandling();

        $concert = Concert::factory()->create();
        $orderA = Order::factory()->create(['amount' => 3850]);
        $orderB = Order::factory()->create(['amount' => 9625]);
        $concert->tickets()->saveMany(Ticket::factory(3)->create(['order_id' => $orderA->id]));
        $concert->tickets()->saveMany(Ticket::factory(2)->create(['order_id' => $orderB->id]));

        $this->assertEquals(134.75, $concert->revenueInDollars());
    }

    /** @test */
    public function trying_to_reserve_more_tickets_than_remain_throws_an_exception()
    {
        $concert = Concert::factory()->create()->addTickets(10);

        try {
            $concert->reserveTickets(11, 'john@example.com');
        } catch (NotEnoughTicketsException $e) {
            $this->assertFalse($concert->hasOrderFor('john@example.com'));

            $this->assertEquals(10, $concert->ticketsRemaining());

            return;
        }

        $this->fail('not enough tickets to purchase');
    }

    /** @test */
    public function can_reserve_available_tickets()
    {
        $concert = Concert::factory()->create()->addTickets(3);
        $this->assertEquals(3, $concert->ticketsRemaining());

        $reservation = $concert->reserveTickets(2, 'john@example.com');

        $this->assertCount(2, $reservation->tickets());
        $this->assertEquals('john@example.com', $reservation->email());
        $this->assertEquals(1, $concert->ticketsRemaining());
    }

    /** @test */
    public function cannot_reserve_tickets_that_have_been_purchased()
    {
        $concert = Concert::factory()->create()->addTickets(3);
        $order = Order::factory()->create();
        
        $order->tickets()->saveMany($concert->tickets->take(2));
        
        try {
            $concert->reserveTickets(2, 'jane@example.com');
        } catch (NotEnoughTicketsException $e) {
            $this->assertEquals(1, $concert->ticketsRemaining());
            return;
        }

        $this->fail('Reserving tickets succeeded even though the tickets were already sold');
    }

    /** @test */
    public function cannot_reserve_tickets_that_have_been_reserved()
    {
        $concert = Concert::factory()->create()->addTickets(3);
        $concert->reserveTickets(2, 'john@example.com');

        try {
            $concert->reserveTickets(2, 'jane@example.com');
        } catch (NotEnoughTicketsException $e) {
            $this->assertEquals(1, $concert->ticketsRemaining());
            return;
        }

        $this->fail('Reserving tickets succeeded even though the tickets were already reserved');
    }
}
