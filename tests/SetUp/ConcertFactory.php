<?php

namespace Tests\SetUp;

use App\Models\Concert;

class ConcertFactory
{
    public static function createPublished($overrides = [])
    {
        $concert = Concert::factory()->create($overrides);
        $concert->publish();
        return $concert;
    }

    public static function createUnpublished($overrides = [])
    {
        return Concert::factory()->unpublished()->create($overrides);
    }
}
