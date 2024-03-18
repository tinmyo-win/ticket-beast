<?php

namespace App\Models;

use App\Facades\OrderConfirmationNumber;
use App\OrderConfirmationNumberGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'amount',
        'confirmation_number',
        'card_last_four',
    ];

    public function concert()
    {
        return $this->belongsToMany(Concert::class, 'tickets');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketsQuantity()
    {
        return $this->tickets()->count();
    }

    public static function forTickets($tickets, $email, $charge)
    {
        $order = self::create([
            'confirmation_number' => OrderConfirmationNumber::generate(),
            'email' => $email,
            'amount' => $charge->amount(),
            'card_last_four' => $charge->cardLastFour(),
        ]);

        // foreach ($tickets as $ticket) {
        //     $order->tickets()->save($ticket);
        // }

        $tickets->each->claimFor($order);

        return $order;
    }

    public static function findByConfirmationNumber($confirmationNumber)
    {
        return Order::where('confirmation_number', $confirmationNumber)
            ->with('concert', 'tickets')
            ->firstOrFail();
    }

    // public function toArray()
    // {
    //     return [
    //         'email' => $this->email,
    //         'amount' => $this->amount,
    //         'confirmation_number' => $this->confirmation_number,
    //         'card_last_four' => $this->card_last_four,
    //         'tickets' => $this->tickets->map(function($ticket) {
    //             return ['code' => $ticket->code];
    //         })->all(),
    //     ];
    // }
}