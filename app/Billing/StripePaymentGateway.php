<?php

namespace App\Billing;
use App\Billing\Charge;
use Error;
use Illuminate\Support\Facades\Log;
use Stripe\Charge as StripeCharge;
use Stripe\Exception\InvalidRequestException;

class StripePaymentGateway implements PaymentGateway
{
    private $apiKey;

    const TEST_CARD_NUMBER = '4242424242424242';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function charge($amount, $token, $destinationAccountId)
    {
        try {
            Log::info($this->apiKey);
            $stripeCharge = StripeCharge::create([
                'amount' => $amount,
                'source' => $token,
                'currency' => 'usd',
                'destination' => [
                    'account' => $destinationAccountId,
                    'amount' => $amount * .9,
                ]
            ], ['api_key'  => $this->apiKey]);

            return new Charge([
                'amount' => $stripeCharge['amount'],
                'card_last_four' => $stripeCharge['source']['last4'],
                'destination' => $destinationAccountId,
            ]);
        } catch(InvalidRequestException $e) {
            Log::info($e->getMessage());
            throw new PaymentFailedException;
        }

    }

    public function getValidTestToken($token = '4242424242424242')
    {
        $tokens = collect([
            '4242424242424242' => 'tok_visa',
            '5555555555554444' => 'tok_mastercard'
        ]);

        return $tokens->get($token, function() {
            throw new Error("You don't have test token for this card number please add");
        });
    }

    private function validToken()
    {
        // $stripe = new \Stripe\StripeClient($this->apiKey);
        // $token = $stripe->tokens->create([
        //     'card' => [
        //         'number' => '4242424242424242',
        //         'exp_month' => '1',
        //         'exp_year' => date('Y') + 1,
        //         'cvc' => '123',
        //     ],
        // ])->id;
    }

    public function newChargesDuring($callback)
    {
        $latestCharge = $this->lastCharge();
        $callback($this);
        $newCharges = $this->newChargesSince($latestCharge);

        return $newCharges->map(function($stripeCharge) {
            return new Charge([
                'amount' => $stripeCharge['amount'],
                'card_last_four' => $stripeCharge['source']['last4'],
            ]);
        });
    }

    private function lastCharge()
    {
        return StripeCharge::all(
            ['limit' => 1],
            ['api_key' => $this->apiKey]
        )['data'][0];
    }

    private function newChargesSince($charge = null)
    {
        $newCharges = StripeCharge::all(
            [
                'ending_before' => $charge ? $charge->id : null,
            ],
            ['api_key' => $this->apiKey]
        )['data'];

        return collect($newCharges);
    }
}
