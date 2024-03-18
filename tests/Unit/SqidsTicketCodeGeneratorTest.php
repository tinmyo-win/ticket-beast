<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\SqidsTicketCodeGenerator;
use PHPUnit\Framework\TestCase;

class SqidsTicketCodeGeneratorTest extends TestCase
{
    /** @test */
    public function tickets_code_are_at_least_6_characters_long()
    {
        $ticketCodeGenerator = new SqidsTicketCodeGenerator;
        $code = $ticketCodeGenerator->generateFor(new Ticket(['id' => 1]));

        $this->assertTrue(strlen($code) >= 6);
    }

    /** @test */
    public function tickets_code_can_only_contains_upper_case()
    {
        $ticketCodeGenerator = new SqidsTicketCodeGenerator;
        $code = $ticketCodeGenerator->generateFor(new Ticket(['id' => 1]));

        $this->assertMatchesRegularExpression('/^[A-Z]+$/', $code);
    }

    /** @test */
    public function tickets_codes_for_the_same_ticket_id_are_the_same()
    {
        $ticketCodeGenerator = new SqidsTicketCodeGenerator;
        
        $code1 = $ticketCodeGenerator->generateFor(new Ticket(['id' => 1]));
        $code2 = $ticketCodeGenerator->generateFor(new Ticket(['id' => 1]));

        $this->assertEquals($code1, $code2);
    }

    /** @test */
    public function tickets_codes_for_the_different_ticket_id_are_different()
    {
        $ticketCodeGenerator = new SqidsTicketCodeGenerator;

        $code1 = $ticketCodeGenerator->generateFor(new Ticket(['id' => 1]));
        $code2 = $ticketCodeGenerator->generateFor(new Ticket(['id' => 2]));

        $this->assertNotEquals($code1, $code2);
    }

    //Now sq id does not support salt

    // /** @test */
    // public function tickets_codes_generated_with_different_salts_are_different()
    // {
    //     $ticketCodeGenerator1 = new SqidsTicketCodeGenerator('testsalt1');
    //     $ticketCodeGenerator2 = new SqidsTicketCodeGenerator('testsalt2');

    //     $code1 = $ticketCodeGenerator1->generateFor(new Ticket(['id' => 1]));
    //     $code2 = $ticketCodeGenerator2->generateFor(new Ticket(['id' => 1]));

    //     $this->assertNotEquals($code1, $code2);
    // }
}
