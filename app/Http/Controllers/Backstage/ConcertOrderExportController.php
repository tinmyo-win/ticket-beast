<?php

namespace App\Http\Controllers\Backstage;

use App\Exports\ConcertOrderExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ConcertOrderExportController extends Controller
{
    public function index()
    {
        return Excel::download(new ConcertOrderExport, 'concert-orders.xlsx');
    }
}
