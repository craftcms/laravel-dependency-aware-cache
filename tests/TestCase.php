<?php

namespace CraftCms\DependencyAwareCache\Tests;

use CraftCms\DependencyAwareCache\CacheServiceProvider;
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
