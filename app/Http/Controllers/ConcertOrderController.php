<?php

namespace App\Http\Controllers;

use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;
use App\Exceptions\NotEnoughTicketsException;
use App\Http\Resources\OrderResource;
use App\Mail\OrderConfirmationEmail;
use App\Models\Concert;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConcertOrderController extends Controller
{
    protected $paymentGateway;

    public function __construct(PaymentGateway $paymentGateWay)
    {
        $this->paymentGateway = $paymentGateWay;
    }
    public function store($concertId)
    {
        $concert = Concert::published()->findOrFail($concertId);

        $this->validate(request(), [
            'email' => ['required'],
        ]);

        try {

            $reservation = $concert->reserveTickets(request('ticket_quantity'), request('email'));
            
            $order = $reservation->complete($this->paymentGateway, request('payment_token'), $concert->user->stripe_account_id);

            Mail::to($order->email)->send(new OrderConfirmationEmail($order));

            $order = OrderResource::make($order);
            return response()->json($order, 201);
            
        } catch (PaymentFailedException $e) {
            $reservation->cancel();
            return response()->json([], 422);
        } catch (NotEnoughTicketsException $e) {
            return response()->json([], 422);
        }
    }
}
