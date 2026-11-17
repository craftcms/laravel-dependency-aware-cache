<?php

namespace CraftCms\DependencyAwareCache\Tests;

use CraftCms\DependencyAwareCache\CacheServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\ParallelTesting;
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

        // Give each parallel test process its own cache directory, so one process's flush can't clear another's entries
        $app['config']->set('cache.stores.file.path', storage_path('framework/cache/data/'.ParallelTesting::token()));
    }
}
