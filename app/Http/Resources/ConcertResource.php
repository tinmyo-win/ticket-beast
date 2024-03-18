<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConcertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'tickets_sold' => $this->ticketsSold(),
            'tickets_remaining' => $this->ticketsRemaining(),
            'total_tickets' => $this->totalTickets(),
            'revenue_in_dollars' => $this->revenueInDollars(),
            'formatted_date' => $this->formatted_date,
            'formatted_start_time' => $this->formatted_start_time,
            'ticket_price_in_dollars' => $this->ticket_price_in_dollars,
        ]);
    }
}
