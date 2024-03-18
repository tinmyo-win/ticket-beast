<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConcertResource;
use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConcertsController extends Controller
{
    public function show(Concert $concert)
    {
        $concert = $concert->published()->where('id', $concert->id)->firstOrFail();

        return Inertia::render('Concert', ['concert' => ConcertResource::make($concert)]);
    }

}
