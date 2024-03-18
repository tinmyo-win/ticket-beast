<?php

namespace Tests\SetUp;

class MyExcel
{
    public static function fake($excel)
    {
        $excel->swap(new MyExcelFake());
    }
}