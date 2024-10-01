<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('app.env', 'testing');

        if (config('app.env') !== 'testing') {
            throw new Exception('This should only be triggered in a testing environment');
        }
    }
}
