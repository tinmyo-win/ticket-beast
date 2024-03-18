<?php

namespace App\Http\Controllers\Backstage;

use App\Events\ConcertAdded;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConcertResource;
use App\Models\Concert;
use App\NullFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ConcertsController extends Controller
{

    public function index()
    {
        $publishedConcerts = Auth::user()->concerts->filter->isPublished();
        $unPublishedConcerts = Auth::user()->concerts->reject->isPublished();
        return Inertia::render('Backstage/Concerts/Index', [
            'publishedConcerts' => ConcertResource::collection($publishedConcerts),
            'unPublishedConcerts' => ConcertResource::collection($unPublishedConcerts),
        ]);
    }

    public function create()
    {
        return Inertia::render('Backstage/Concerts/Create');
    }

    public function store()
    {
        $this->validate(request(), [
            'title' => ['required'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:g:ia'],
            'venue' => ['required'],
            'venue_address' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'zip' => ['required'],
            'ticket_price' => ['required', 'numeric', 'min:5'],
            'ticket_quantity' => ['required', 'numeric', 'min:1'],
            'poster_image' => ['nullable', 'image', Rule::dimensions()->minWidth(600)],

            //, Rule::dimensions()->minWidth(600)->ratio(8.5/11)
        ]);

        $concert = Auth::user()->concerts()->create([
            'title' => request('title'),
            'subtitle' => request('subtitle'),
            'additional_information' => request('additional_information'),
            'date' => Carbon::parse(vsprintf('%s %s', [
                request('date'),
                request('time')
            ])),
            'venue' => request('venue'),
            'venue_address' => request('venue_address'),
            'city' => request('city'),
            'state' => request('state'),
            'zip' => request('zip'),
            'ticket_price' => request('ticket_price') * 100,
            'ticket_quantity' => (int) request('ticket_quantity'),
            'poster_image_path' => request('poster_image', new NullFile)->store('posters',  'public'),
        ]);

        ConcertAdded::dispatch($concert);

        return redirect()->route('backstage.concerts.index');
    }

    public function edit($id)
    {
        $concert = Auth::user()->concerts()->findOrFail($id);

        abort_if($concert->isPublished(), 403);

        return Inertia::render('Backstage/Concerts/Edit', ['concert' => $concert]);
    }

    public function update($id)
    {
        $this->validate(request(), [
            'title' => ['required'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:g:ia'],
            'venue' => ['required'],
            'venue_address' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'zip' => ['required'],
            'ticket_price' => ['required', 'numeric', 'min:5'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],

        ]);

        $concert = Auth::user()->concerts()->findOrFail($id);

        abort_if($concert->isPublished(), 403);

        $concert->update([
            'title' => request('title'),
            'subtitle' => request('subtitle'),
            'additional_information' => request('additional_information'),
            'date' => Carbon::parse(vsprintf('%s %s', [
                request('date'),
                request('time')
            ])),
            'venue' => request('venue'),
            'venue_address' => request('venue_address'),
            'city' => request('city'),
            'state' => request('state'),
            'zip' => request('zip'),
            'ticket_price' => request('ticket_price') * 100,
            'ticket_quantity'  => (int) request('ticket_quantity'),
        ]);

        return redirect()->route('backstage.concerts.index');
    }
}
