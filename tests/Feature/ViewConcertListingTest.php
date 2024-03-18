<?php

namespace Tests\Feature;

use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewConcertListingTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_view_a_published_consert_listing()
    {
        $this->withoutExceptionHandling();
        //Arrange
        //Create concert

        $concert = Concert::factory()->published()->create([
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
        ]);

        //Act
        //View the concert list
        $response = $this->get('concerts/' . $concert->id);

        //Assertion
        //See the concert listing
        $response->assertSee('The Red Chord');
        $response->assertSee('with Lay Phyu and A Nge');
        $response->assertSee('March 13, 2024');
        $response->assertSee('8:00pm');
        $response->assertSee('6,000.00');
        $response->assertSee('KanTawGyi');
        $response->assertSee('1234 Street');
        $response->assertSee('Yangon');
        $response->assertSee('YG');
        $response->assertSee('33556');
        $response->assertSee('For tickets, call 555 - (5555)');

    }

    /** @test */
    public function user_cannot_view_unpublished_concert_listings()
    {
        $concert = Concert::factory()->unpublished()->create();

        $response = $this->get('concerts/' . $concert->id);

        $response->assertStatus(404);
    }
}
