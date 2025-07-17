<?php

namespace CraftCms\DependencyAwareCache;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /**
         * Register Laravel's service provider, that way ours runs after.
         */
        $this->app->register(\Illuminate\Cache\CacheServiceProvider::class);

        $this->app->singleton('cache', function ($app) {
            /** @phpstan-ignore larastan.octaneCompatibility */
            return new DependencyAwareCacheManager($app);
        });

        Cache::clearResolvedInstances();
    }
}
