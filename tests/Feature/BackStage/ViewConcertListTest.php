<?php

namespace Tests\Feature\BackStage;

use App\Models\Concert;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Assert;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ViewConcertListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // TestResponse::macro('data', function($key) {
        //     return $this->original->getData()['page']['props'][$key];
        // });

    }

    /** @test */
    public function guests_cannot_view_a_promoters_concert_list()
    {
        $response = $this->get('backstage/concerts');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function promoters_can_only_view_a_list_of_their_own_concerts()
    {
        $this->withoutExceptionHandling();
        //should use macro for testing
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherConcert = Concert::factory()->create(['user_id' => $otherUser->id]);
        $publishedConcerts = Concert::factory(3)->published()->create(['user_id' => $user->id]);
        $otherConcert = Concert::factory()->create(['user_id' => $otherUser->id]);

        $otherUnpublishedConcert = Concert::factory()->create(['user_id' => $otherUser->id]);
        $unPublishedConcerts = Concert::factory(3)->unpublished()->create(['user_id' => $user->id]);
        $otherUnpublishedConcert = Concert::factory()->create(['user_id' => $otherUser->id]);


        $response = $this->actingAs($user)->get('/backstage/concerts');

        $response->assertStatus(200);

        $publishedConcerts = $publishedConcerts->map(function ($concert) {
            $concert->formatted_date = $concert->formatted_date;
            $concert->formatted_start_time = $concert->formatted_start_time;
            $concert->ticket_price_in_dollars = $concert->ticket_price_in_dollars;
            $concert->tickets_remaining = $concert->ticketsRemaining();
            $concert->tickets_sold = $concert->ticketsSold();
            $concert->total_tickets = $concert->totalTickets();
            $concert->revenue_in_dollars = $concert->revenueInDollars();

            return $concert;
        });

        $unPublishedConcerts = $unPublishedConcerts->map(function ($concert) {
            $concert->formatted_date = $concert->formatted_date;
            $concert->formatted_start_time = $concert->formatted_start_time;
            $concert->ticket_price_in_dollars = $concert->ticket_price_in_dollars;
            $concert->tickets_remaining = $concert->ticketsRemaining();
            $concert->tickets_sold = $concert->ticketsSold();
            $concert->total_tickets = $concert->totalTickets();
            $concert->revenue_in_dollars = $concert->revenueInDollars();

            return $concert;
        });

        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Backstage/Concerts/Index')
                ->has('publishedConcerts', 3)
                ->where('publishedConcerts', $publishedConcerts)
                ->has('unPublishedConcerts', 3)
                ->where('unPublishedConcerts', $unPublishedConcerts)
        );
    }
}
