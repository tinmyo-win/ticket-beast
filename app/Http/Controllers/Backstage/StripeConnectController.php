<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class StripeConnectController extends Controller
{
    public function authorizeRedirect()
    {
        $url = vsprintf('%s?%s', [
            'https://connect.stripe.com/oauth/v2/authorize',
            http_build_query([
                'response_type' => 'code',
                'scope' => 'read_write',
                'client_id' => config('services.stripe.client_id')
            ])
        ]);
        return Inertia::location($url);
    }

    public function redirect()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $accessTokenResponse = \Stripe\OAuth::token([
            'grant_type' => 'authorization_code',
            'code' => request('code'),
        ]);

        Auth::user()->update([
            'stripe_account_id' => $accessTokenResponse['stripe_user_id'],
            'stripe_access_token' => $accessTokenResponse['access_token'],
        ]);

        return redirect(route('backstage.concerts.index'));
    }

    public function connect()
    {
        return Inertia::render('Backstage/StripeConnect/Connect');
    }
}
