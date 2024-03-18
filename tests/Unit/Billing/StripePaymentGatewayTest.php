<?php

namespace Tests\Unit\Billing;

use App\Billing\PaymentFailedException;
use App\Billing\StripePaymentGateway;
use Carbon\Carbon;
use Stripe\Charge as StripeCharge;
use Stripe\Transfer as StripeTransfer;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @group integration
 */
class StripePaymentGatewayTest extends TestCase
{
    use PaymentGatewayContractTests;

    protected function getPaymentGateway()
    {
        return new StripePaymentGateway(config('services.stripe.secret'));
    }

    /** @test */
    public function ninety_percent_of_the_payment_is_transferred_to_the_destination_account()
    {
        $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));
        $paymentGateway->charge(5000, $paymentGateway->getValidTestToken(), env('STRIPE_TEST_PROMOTER_ID'));

        $lastStripeCharge = StripeCharge::all(
            ['limit' => 1],
            ['api_key' => config('services.stripe.secret')]
        )['data'][0];

        $this->assertEquals(5000, $lastStripeCharge['amount']);
        $this->assertEquals(env('STRIPE_TEST_PROMOTER_ID'), $lastStripeCharge['destination']);
        
        $transfer = StripeTransfer::retrieve($lastStripeCharge['transfer'], ['api_key' => config('services.stripe.secret')] );
        $this->assertEquals(4500, $transfer['amount']);
    }
}
