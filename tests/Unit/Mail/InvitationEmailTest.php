<?php

namespace Tests\Unit\Mail;

use App\Mail\InvitationEmail;
use App\Models\Invitation;
use Tests\TestCase;

class InvitationEmailTest extends TestCase
{
    /** @test */
    public function email_contains_a_link_to_accept_the_invitation()
    {
        $this->withoutExceptionHandling();

        $invitation = Invitation::factory()->make([
            'code' => 'TESTCODE1234',
            'email' => 'john@example.com',
        ]);

        $email = new InvitationEmail($invitation);

        $email->assertSeeInHtml(url('/invitations/TESTCODE1234'));
    }

    /** @test */
    public function email_contains_the_correct_subject()
    {
        $this->withoutExceptionHandling();

        $invitation = Invitation::factory()->make();

        $email = new InvitationEmail($invitation);

        $email->assertHasSubject("You're invited to join TicketBeast!");
    }
}
