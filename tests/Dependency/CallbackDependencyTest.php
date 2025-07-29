<?php

use CraftCms\DependencyAwareCache\Dependency\CallbackDependency;
use CraftCms\DependencyAwareCache\Facades\DependencyCache;

test('plain closure', function () {
    $dependency = new CallbackDependency(static fn () => true);

    setInaccessibleProperty($dependency, 'data', true);

    expect($dependency->isChanged(DependencyCache::store()))->toBeFalse();
});

test('closure with cache', function () {
    $dependency = new CallbackDependency(static fn (\Illuminate\Cache\Repository $cache) => $cache::class);

    setInaccessibleProperty($dependency, 'data', \CraftCms\DependencyAwareCache\DependencyAwareRepository::class);

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

it('can be serialized', function () {
    $dependency = new CallbackDependency(static fn () => '');

    expect(serialize($dependency))->toBeString();
});
