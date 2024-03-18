<?php

namespace Tests\Unit\Mail;

use App\Mail\OrderConfirmationEmail;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderConfirmationEmailTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function email_contains_a_link_to_the_confirmation_page()
    {
        $this->withoutExceptionHandling();

        $order = Order::factory()->create([
            'confirmation_number' => 'ORDERCONFIRMATION1234',
        ]);

        $email = new OrderConfirmationEmail($order);

        $email->assertSeeInHtml('/orders/ORDERCONFIRMATION1234');
    }

    /** @test */
    public function email_has_a_subject()
    {
        $order = Order::factory()->create();
        $email = new OrderConfirmationEmail($order);

        $email->assertHasSubject('Your TicketBeast Order');
    }
}
