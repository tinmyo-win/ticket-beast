<?php

namespace Tests\Feature\BackStage;

use App\Models\Concert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublishConcertTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_promoter_can_publish_their_own_concert()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $concert = Concert::factory()->unpublished()->create([
            'user_id' => $user->id,
            'ticket_quantity' => 3
        ]);

        $response = $this->actingAs($user)->post('/backstage/published-concerts', [
            'concert_id' => $concert->id,
            'ticket_quantity' => 3,
        ]);

        $response->assertRedirect('/backstage/concerts');
        $this->assertTrue($concert->fresh()->isPublished());
        $this->assertEquals(3, $concert->fresh()->ticketsRemaining());
    }

    /** @test */
    public function a_concert_can_be_published_only_once()
    {
        $user = User::factory()->create();
        $concert = Concert::factory()->published()->create([
            'user_id' => $user->id,
            'ticket_quantity' => 3
        ])->addTickets(3);

        $response = $this->actingAs($user)->post('/backstage/published-concerts', [
            'concert_id' => $concert->id,
            'ticket_quantity' => 3,
        ]);

        $response->assertStatus(422);
        $this->assertEquals(3, $concert->fresh()->ticketsRemaining());
    }

    /** @test */
    function a_promoter_cannot_publish_other_concerts()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $concert = Concert::factory()->unpublished()->create([
            'user_id' => $otherUser->id,
            'ticket_quantity' => 3,
        ]);

        $response = $this->actingAs($user)->post('/backstage/published-concerts', [
            'concert_id' => $concert->id,
        ]);

        $response->assertStatus(404);
        $concert = $concert->fresh();
        $this->assertFalse($concert->isPublished());
        $this->assertEquals(0, $concert->ticketsRemaining());
    }

    /** @test */
    function a_guest_cannot_publish_concerts()
    {
        $concert = Concert::factory()->unpublished()->create([
            'ticket_quantity' => 3,
        ]);

        $response = $this->post('/backstage/published-concerts', [
            'concert_id' => $concert->id,
        ]);

        $response->assertRedirect('/login');
        $concert = $concert->fresh();
        $this->assertFalse($concert->isPublished());
        $this->assertEquals(0, $concert->ticketsRemaining());
    }

    /** @test */
    function concerts_that_do_not_exist_cannot_be_published()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/backstage/published-concerts', [
            'concert_id' => 999,
        ]);

        $response->assertStatus(404);
    }
}
