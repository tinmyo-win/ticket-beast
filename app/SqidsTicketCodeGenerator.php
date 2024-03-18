<?php

namespace App;

use Sqids\Sqids;

class SqidsTicketCodeGenerator implements TicketCodeGenerator
{
    private $sqIds;

    public function __construct()
    {
        $this->sqIds = new Sqids(alphabet: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',minLength: 6, );
    }
    public function generateFor($ticket)
    {
        return $this->sqIds->encode([$ticket->id]);
    }
}
