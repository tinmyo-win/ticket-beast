<?php

namespace App\Billing;
use Illuminate\Support\Str;

class FakePaymentGateway implements PaymentGateway
{
    private $charges;
    private $tokens;

    const TEST_CARD_NUMBER = '4242424242424242';

    private $beforeFirstChargeCallback;

    public function __construct()
    {
        $this->charges = collect();
        $this->tokens = collect();
    }
    public function getValidTestToken($cardNumber = '4242424242424242')
    {
        $token = 'fake-tok_' . Str::random(24);
        $this->tokens[$token] = $cardNumber;
        return $token;
    }

    public function charge($amount, $token, $destinationAccountId)
    {
        if ($this->beforeFirstChargeCallback !== null) {
            $callback = $this->beforeFirstChargeCallback;
            $this->beforeFirstChargeCallback = null;
            $callback($this);
        }

        if (!$this->tokens->has($token)) {
            throw new PaymentFailedException();
        }

        return $this->charges[] = new Charge(
            [
                'amount' => $amount,
                'card_last_four' => substr($this->tokens[$token], -4),
                'destination' => $destinationAccountId,
            ]
        );
    }

    public function newChargesDuring($callback)
    {
        $chargesFrom = $this->charges->count();

        $callback($this);

        return $this->charges->slice($chargesFrom)->reverse()->values();
    }

    public function totalCharges()
    {
        return $this->charges->map->amount()->sum();
    }

    public function totalChargesFor($accountId)
    {
        return $this->charges->filter(function ($charge) use ($accountId) {
            return $charge->destination() === $accountId;
        })->map->amount()->sum();
    }

    public function beforeFirstCharge($callback)
    {
        $this->beforeFirstChargeCallback = $callback;
    }
}
