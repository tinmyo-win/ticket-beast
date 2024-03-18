<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConcertResource;
use App\Http\Resources\OrderResource;
use App\Models\Concert;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PublishedConcertOrdersController extends Controller
{
    public function index($concertId)
    {
        $concert = Auth::user()->concerts()->published()->findOrFail($concertId);

        return Inertia::render('Backstage/PublishedCocnertOrders/Index', [
            'concert' => ConcertResource::make($concert),
            'orders' => OrderResource::collection($concert->orders()->latest()->take(10)->get()),
        ]);
    }
}
