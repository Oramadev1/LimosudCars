<?php

namespace Tests\Unit;

use App\Support\IdentityDocument;
use Tests\TestCase;

class IdentityDocumentTest extends TestCase
{
    public function test_it_normalizes_passport_or_cin_formats_to_the_same_value(): void
    {
        $this->assertSame('AA123456', IdentityDocument::normalize('aa123456'));
        $this->assertSame('AA123456', IdentityDocument::normalize('AA 123-456'));
        $this->assertSame('AA123456', IdentityDocument::normalize('AA.123.456'));
    }
}
