<?php

namespace Tests\SetUp;

use Illuminate\Testing\Assert;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Fakes\ExcelFake;

class MyExcelFake extends ExcelFake
{
    public function assertHasHeadings($headings = [], $fileName)
    {
        $export = $this->downloads[$fileName];

        Assert::assertInstanceOf(WithHeadings::class, $export);
        Assert::assertEquals($headings, $export->headings());
    }

    public function assertHasRows($rows = [], $fileName, $rowCount=1)
    {
        $export = $this->downloads[$fileName];

        Assert::assertEquals($rowCount, $export->query()->count());
        Assert::assertInstanceOf(WithMapping::class, $export);

        foreach($export->query()->get() as $key => $row) {
            $expected = $export->map($row);

            Assert::assertEquals($rows[$key], $expected);
        }
    }
}
