<?php

namespace Tests\Feature\BackStage;

use App\Events\ConcertAdded;
use App\Models\Concert;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Tests\TestCase;

class AddConcertTest extends TestCase
{
    use RefreshDatabase;

    private function validParams($overrides = [])
    {
        return array_merge([
            'title' => 'No Warning',
            'subtitle' => 'with Cruel Hand and Backtrack',
            'additional_information' => "You must be 19 years of age to attend this concert.",
            'date' => '2017-11-18',
            'time' => '8:00pm',
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Fake St.',
            'city' => 'Laraville',
            'state' => 'ON',
            'zip' => '12345',
            'ticket_price' => '32.50',
            'ticket_quantity' => '75',
        ], $overrides);
    }

    /** @test */
    public function promoters_can_view_the_add_concert_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('backstage.concerts.new'));

        $response->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_add_concert_form(): void
    {

        $response = $this->get(route('backstage.concerts.new'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function adding_valid_concert()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/backstage/concerts', [
            'title' => 'No Warning',
            'subtitle' => 'with Cruel Hand and Backtrack',
            'additional_information' => "You must be 19 years of age to attend this concert.",
            'date' => '2017-11-18',
            'time' => '8:00pm',
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Fake St.',
            'city' => 'Laraville',
            'state' => 'ON',
            'zip' => '12345',
            'ticket_price' => '32.50',
            'ticket_quantity' => '75',
        ]);

        tap(Concert::first(), function ($concert) use ($response, $user) {
            $response->assertRedirect("backstage/concerts");

            $this->assertTrue($concert->user->is($user));

            $this->assertFalse($concert->isPublished());

            $this->assertEquals('No Warning', $concert->title);
            $this->assertEquals('with Cruel Hand and Backtrack', $concert->subtitle);
            $this->assertEquals("You must be 19 years of age to attend this concert.", $concert->additional_information);
            $this->assertEquals(Carbon::parse('2017-11-18 8:00pm'), $concert->date);
            $this->assertEquals('The Mosh Pit', $concert->venue);
            $this->assertEquals('123 Fake St.', $concert->venue_address);
            $this->assertEquals('Laraville', $concert->city);
            $this->assertEquals('ON', $concert->state);
            $this->assertEquals('12345', $concert->zip);
            $this->assertEquals(3250, $concert->ticket_price);
            $this->assertEquals(75, $concert->ticket_quantity);
            $this->assertEquals(0, $concert->ticketsRemaining());
        });
    }

    /** @test */
    public function guests_cannot_add_new_concerts()
    {
        $response = $this->post('/backstage/concerts', $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    public function title_is_required()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'title' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('title');

        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    public function subtitle_is_optional()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/backstage/concerts', $this->validParams([
            'subtitle' => '',
        ]));

        tap(Concert::first(), function ($concert) use ($response, $user) {
            $response->assertRedirect("/backstage/concerts");

            $this->assertTrue($concert->user->is($user));

            $this->assertNull($concert->subtitle);
        });
    }

    /** @test */
    function additional_information_is_optional()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/backstage/concerts', $this->validParams([
            'additional_information' => "",
        ]));

        tap(Concert::first(), function ($concert) use ($response, $user) {
            $response->assertRedirect("/backstage/concerts");

            $this->assertTrue($concert->user->is($user));

            $this->assertNull($concert->additional_information);
        });
    }

    /** @test */
    function date_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'date' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function date_must_be_a_valid_date()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'date' => 'not a date',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function time_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'time' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('time');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function time_must_be_a_valid_time()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'time' => 'not-a-time',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('time');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function venue_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'venue' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('venue');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function venue_address_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'venue_address' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('venue_address');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function city_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'city' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('city');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function state_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'state' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function zip_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'zip' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('zip');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_price_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_price' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_price');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_price_must_be_numeric()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_price' => 'not a price',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_price');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_price_must_be_at_least_5()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_price' => '4.99',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_price');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_quantity_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_quantity' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_quantity');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_quantity_must_be_numeric()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_quantity' => 'not a number',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_quantity');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_quantity_must_be_at_least_1()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_quantity' => '0',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_quantity');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    public function poster_image_is_uploaded_if_included()
    {
        $this->withoutExceptionHandling();
        Storage::fake('public');

        $user = User::factory()->create();

        $file = File::image('concert-poster.png', 850, 1100);
        optimize_file($file);

        $response = $this->actingAs($user)->post('/backstage/concerts', $this->validParams([
            'poster_image' => $file,
        ]));

        tap(Concert::first(), function ($concert) use ($file) {
            $this->assertNotNull($concert->poster_image_path);
            Storage::disk('public')->assertExists($concert->poster_image_path);
            $this->assertFileEquals(
                $file->getPathname(),
                Storage::disk('public')->path($concert->poster_image_path)
            );
        });
    }

    /** @test */
    function poster_image_must_be_an_image()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $file = File::create('not-a-poster.pdf');

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'poster_image' => $file,
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('poster_image');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function poster_image_must_be_at_least_600px_wide()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $file = File::image('poster.png', 599);

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'poster_image' => $file,
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('poster_image');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function poster_image_is_optional()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/backstage/concerts', $this->validParams([
            'poster_image' => null,
        ]));

        tap(Concert::first(), function ($concert) use ($response, $user) {
            $response->assertRedirect('/backstage/concerts');

            $this->assertTrue($concert->user->is($user));

            $this->assertNull($concert->poster_image_path);
        });
    }

    /** @test */
    function an_event_is_fired_when_a_concert_is_added()
    {
        $this->withoutExceptionHandling();

        Event::fake([ConcertAdded::class]);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/backstage/concerts', $this->validParams());

        Event::assertDispatched(ConcertAdded::class, function ($event) {
            $concert = Concert::firstOrFail();
            return $event->concert->is($concert);
        });
    }
}

function optimize_file(&$file)
{
    $image = ImageManager::gd()->read($file->getPathname());
    $image->resize(600, 776)->reduceColors(255);
    file_put_contents($file->getPathname(), $image->encode());
}
