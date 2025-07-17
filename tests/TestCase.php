<?php

namespace Craft\DependencyAwareCache\Tests;

use Craft\DependencyAwareCache\CacheServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CacheServiceProvider::class,
        ];
    }
}
