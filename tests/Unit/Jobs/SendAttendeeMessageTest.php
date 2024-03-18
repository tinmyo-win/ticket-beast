<?php

namespace Tests\Unit\Jobs;

use App\Jobs\SendAttendeeMessage;
use App\Mail\AttendeeMessageEmail;
use App\Models\AttendeeMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tests\SetUp\ConcertFactory;
use Tests\SetUp\OrderFactory;

class SendAttendeeMessageTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_send_the_message_to_all_concert_attendees(): void
    {
        Mail::fake();

        $concert = ConcertFactory::createPublished();
        $otherConcert = ConcertFactory::createPublished();

        $message = AttendeeMessage::create([
            'concert_id' => $concert->id,
            'subject' => 'My Subject',
            'message' => 'My Message',
        ]);

        $orderA = OrderFactory::createForConcert($concert, ['email' => 'alex@example.com']);
        $otherOrder = OrderFactory::createForConcert($otherConcert, ['email' => 'jane@example.com']);
        $orderB = OrderFactory::createForConcert($concert, ['email' => 'sam@example.com']);
        $orderC = OrderFactory::createForConcert($concert, ['email' => 'dean@example.com']);

        SendAttendeeMessage::dispatch($message);

        Mail::assertQueued(AttendeeMessageEmail::class, function($mail) use($message) {
            return $mail->hasTo('alex@example.com')
                && $mail->attendeeMessage->is($message);
        });

        Mail::assertQueued(AttendeeMessageEmail::class, function ($mail) use ($message) {
            return $mail->hasTo('sam@example.com')
            && $mail->attendeeMessage->is($message);
        });

        Mail::assertQueued(AttendeeMessageEmail::class, function ($mail) use ($message) {
            return $mail->hasTo('dean@example.com')
            && $mail->attendeeMessage->is($message);
        });

        Mail::assertNotSent(AttendeeMessageEmail::class, function ($mail) use ($message) {
            return $mail->hasTo('jane@example.com');
        });
    }
}
