<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Concerns\AuthenticatesAdmin;

abstract class TestCase extends BaseTestCase
{
    use AuthenticatesAdmin;
}
