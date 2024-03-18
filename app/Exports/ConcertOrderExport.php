<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConcertOrderExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Email',
            'Amount',
            'Total Tickets',
            'Concert',
            'Purchased Date'
        ];
    }
    
    public function query()
    {
        return Order::query();
    }

    public function map($row): array
    {
        return [
            $row->email,
            $row->amount,
            $row->ticketsQuantity(),
            $row->concert()->first()->title,
            $row->created_at
        ];
    }
}
