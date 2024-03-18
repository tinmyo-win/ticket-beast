<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class AcceptInvitationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function viewing_an_unused_invitation()
    {
        $this->withoutExceptionHandling();
        $invitation = Invitation::factory()->create([
            'user_id' => null,
            'code' => 'TESTCODE1234',
        ]);

        $response = $this->get('/invitations/TESTCODE1234');

        $response->assertStatus(200);

        $response->assertInertia(
            fn (AssertableInertia $page) =>
            $page
                ->component('Invitations/Show')
                ->where('invitation', $invitation)

        );
    }

    /** @test */
    public function viewing_a_used_invitation()
    {
        // $this->withoutExceptionHandling();

        $invitation = Invitation::factory()->create([
            'user_id' => User::factory(),
            'code' => 'TESTCODE1234',
        ]);

        $response = $this->get('/invitations/TESTCODE1234');

        $response->assertStatus(404);
    }

    /** @test */
    public function viewing_an_invitation_that_does_not_exist()
    {
        $response = $this->get('/invitations/TESTCODE1234');

        $response->assertStatus(404);
    }

    /** @test */
    function registering_with_a_valid_invitation_code()
    {
        $this->withoutExceptionHandling();

        $invitation = Invitation::factory()->create([
            'user_id' => null,
            'code' => 'TESTCODE1234',
        ]);

        $response = $this->post(route('promoters.register',['invitation_code' => 'TESTCODE1234']), [
            'email' => 'john@example.com',
            'password' => 'secret',
            'invitation_code' => 'TESTCODE1234',
        ]);

        $response->assertRedirect('/backstage/concerts');

        $this->assertEquals(1, User::count());
        $user = User::first();
        $this->assertAuthenticatedAs($user);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('secret', $user->password));
        $this->assertTrue($invitation->fresh()->user->is($user));
    }

    /** @test */
    function registering_with_a_used_invitation_code()
    {
        $invitation = Invitation::factory()->create([
            'user_id' => User::factory()->create(),
            'code' => 'TESTCODE1234',
        ]);
        $this->assertEquals(1, User::count());

        $response = $this->post(route('promoters.register',['invitation_code' => 'TESTCODE1234']), [
            'email' => 'john@example.com',
            'password' => 'secret',
            'invitation_code' => 'TESTCODE1234',
        ]);

        $response->assertStatus(404);
        $this->assertEquals(1, User::count());
    }

    /** @test */
    function registering_with_an_invitation_code_that_does_not_exist()
    {
        $response = $this->post(route('promoters.register',['invitation_code' => 'TESTCODE1234']), [
            'email' => 'john@example.com',
            'password' => 'secret',
            'invitation_code' => 'TESTCODE1234',
        ]);

        $response->assertStatus(404);
        $this->assertEquals(0, User::count());
    }

    /** @test */
    function email_is_required()
    {
        $invitation = Invitation::factory()->create([
            'user_id' => null,
            'code' => 'TESTCODE1234',
        ]);

        $response = $this->from('/invitations/TESTCODE1234')->post(route('promoters.register',['invitation_code' => 'TESTCODE1234']), [
            'email' => '',
            'password' => 'secret',
            'invitation_code' => 'TESTCODE1234',
        ]);

        $response->assertRedirect('/invitations/TESTCODE1234');
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, User::count());
    }

    /** @test */
    function email_must_be_an_email()
    {
        $invitation = Invitation::factory()->create([
            'user_id' => null,
            'code' => 'TESTCODE1234',
        ]);

        $response = $this->from('/invitations/TESTCODE1234')->post(route('promoters.register',['invitation_code' => 'TESTCODE1234']), [
            'email' => 'not-an-email',
            'password' => 'secret',
            'invitation_code' => 'TESTCODE1234',
        ]);

        $response->assertRedirect('/invitations/TESTCODE1234');
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, User::count());
    }

    /** @test */
    function email_must_be_unique()
    {
        // $this->withoutExceptionHandling();
        $existingUser = User::factory()->create(['email' => 'john@example.com']);
        $this->assertEquals(1, User::count());

        $invitation = Invitation::factory()->create([
            'user_id' => null,
            'code' => 'TESTCODE1234',
        ]);

        $response = $this->from('/invitations/TESTCODE1234')->post(route('promoters.register',['invitation_code' => 'TESTCODE1234']), [
            'email' => 'john@example.com',
            'password' => 'secret',
            'invitation_code' => 'TESTCODE1234',
        ]);

        $response->assertRedirect('/invitations/TESTCODE1234');
        $response->assertSessionHasErrors('email');
        $this->assertEquals(1, User::count());
    }

    /** @test */
    function password_is_required()
    {
        $invitation = Invitation::factory()->create([
            'user_id' => null,
            'code' => 'TESTCODE1234',
        ]);

        $response = $this->from('/invitations/TESTCODE1234')->post(route('promoters.register',['invitation_code' => 'TESTCODE1234']), [
            'email' => 'john@example.com',
            'password' => '',
            'invitation_code' => 'TESTCODE1234',
        ]);

        $response->assertRedirect('/invitations/TESTCODE1234');
        $response->assertSessionHasErrors('password');
        $this->assertEquals(0, User::count());
    }
}
