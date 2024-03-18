<?php

namespace Tests\Feature\Backstage\Exports;

use App\Exports\ConcertOrderExport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;
use Tests\SetUp\ConcertFactory;
use Tests\SetUp\MyExcel;
use Tests\SetUp\OrderFactory;
use Tests\TestCase;

class ConcertOrderExportTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_promoter_can_download_export_file_of_his_concert_orders()
    {
        $this->withoutExceptionHandling();
        MyExcel::fake(new Excel);

        $user = User::factory()->create();

        $concert = ConcertFactory::createPublished([
            'title' => 'My Concert',
            'user_id' => $user->id,
        ]);

        $order = OrderFactory::createForConcert($concert, [
            'email' => 'john@example.com',
            'amount' => 6000,
            'created_at' => Carbon::now()->toDateTimeString(),
        ], 2);

        $this->actingAs($user)->get("backstage/concerts/{$concert->id}/orders/download");

        Excel::assertHasHeadings([
            'Email',
            'Amount',
            'Total Tickets',
            'Concert',
            'Purchased Date',
        ], 'concert-orders.xlsx');

        Excel::assertHasRows([
            [
                'john@example.com',
                6000,
                2,
                'My Concert',
                now()->toDateTimeString(),
            ]
            ],'concert-orders.xlsx', 1);

        Excel::assertDownloaded('concert-orders.xlsx');
    }
}
