<?php

namespace Tests\Unit;

use App\Services\CustomerService;
use App\Support\PhoneNumber;
use Tests\TestCase;

class PhoneNumberTest extends TestCase
{
    public function test_it_normalizes_moroccan_phone_formats_to_the_same_value(): void
    {
        $this->assertSame('212635354343', PhoneNumber::normalize('0635354343'));
        $this->assertSame('212635354343', PhoneNumber::normalize('+212635354343'));
        $this->assertSame('212635354343', PhoneNumber::normalize('212635354343'));
    }
}
