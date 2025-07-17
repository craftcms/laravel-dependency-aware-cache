<?php

use Craft\DependencyAwareCache\Dependency\CallbackDependency;
use Craft\DependencyAwareCache\Facades\DependencyCache;

test('plain closure', function () {
    $dependency = new CallbackDependency(static fn () => true);

    setInaccessibleProperty($dependency, 'data', true);

    expect($dependency->isChanged(DependencyCache::store()))->toBeFalse();
});

test('closure with cache', function () {
    $dependency = new CallbackDependency(static fn (\Illuminate\Cache\Repository $cache) => $cache::class);

    setInaccessibleProperty($dependency, 'data', \Craft\DependencyAwareCache\DependencyAwareRepository::class);

    expect($dependency->isChanged(DependencyCache::store()))->toBeFalse();
});

test('scope with object', function () {
    $dataObject = new class
    {
        public string $value = 'value';
    };

    $dependency = new CallbackDependency(static fn () => $dataObject->value);

    setInaccessibleProperty($dependency, 'data', 'value');

    expect($dependency->isChanged(DependencyCache::store()))->toBeFalse();

    $dataObject->value = 'new-value';

    expect($dependency->isChanged(DependencyCache::store()))->toBeTrue();
});
