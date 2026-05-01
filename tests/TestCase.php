<?php

namespace CraftCms\DependencyAwareCache\Tests;

use CraftCms\DependencyAwareCache\CacheServiceProvider;
use Illuminate\Support\Facades\Cache;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    protected function tearDown(): void
    {
        Cache::flush();

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        $app['config']->set('cache.serializable_classes', false); // Laravel skeleton default

        return [
            CacheServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('cache.default', 'file');
    }
}
