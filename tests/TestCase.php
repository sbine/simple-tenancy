<?php

namespace Sbine\Tenancy\Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Sbine\Tenancy\TenancyProvider;

class TestCase extends TestbenchTestCase
{
    protected function getPackageProviders($app)
    {
        return [TenancyProvider::class];
    }
}
