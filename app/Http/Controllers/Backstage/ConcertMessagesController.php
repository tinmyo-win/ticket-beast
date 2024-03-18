<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConcertResource;
use App\Jobs\SendAttendeeMessage;
use App\Models\Concert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConcertMessagesController extends Controller
{
    public function create($id)
    {
        $concert = Auth::user()->concerts()->findOrFail($id);

        return Inertia::render('Backstage/ConcertMessages/Create', ['concert' => ConcertResource::make($concert)]);
    }

    public function store($id)
    {
        $this->validate(request(), [
            'subject' => ['required', 'string'],
            'message' => ['required', 'string']
        ]);

        $concert = Auth::user()->concerts()->findOrFail($id);

        $message = $concert->attendeeMessages()->create(request(['subject', 'message']));
        
        SendAttendeeMessage::dispatch($message);
        
        return redirect()->route('backstage.concert-messages.new', ['id' => $concert->id])
            ->with('flash', 'Your Message has been sent.');
    }
}
