<?php

namespace Tests\Unit\Mail;

use App\Mail\AttendeeMessageEmail;
use App\Models\AttendeeMessage;
use Tests\TestCase;

class AttendeeMessageEmailTest extends TestCase
{
    /** @test */
    function email_has_the_correct_subject_and_message()
    {
        $message = new AttendeeMessage([
            'subject' => 'My Subject',
            'message' => 'My Message',
        ]);
        $email = new AttendeeMessageEmail($message);

        $email->assertHasSubject("My Subject");
        $email->assertSeeInHtml("My Message");
    }

}
