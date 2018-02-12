<?php

namespace NathanHeffley\Analytics\Tests;

use Mockery;
use Orchestra\Testbench\TestCase;

abstract class UnitTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    protected function getPackageProviders($app)
    {
        return [
            'NathanHeffley\Analytics\AnalyticsServiceProvider',
        ];
    }
}
