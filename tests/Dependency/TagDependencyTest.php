<?php

use CraftCms\DependencyAwareCache\Dependency\TagDependency;
use CraftCms\DependencyAwareCache\Facades\DependencyCache;

test('invalidate by tag', function () {
    DependencyCache::rememberForever('item_42_price', static fn () => 13, new TagDependency('item_42'));
    DependencyCache::rememberForever('item_42_total', static fn () => 26, new TagDependency('item_42'));

    expect(DependencyCache::rememberForever('item_42_price', static fn () => 26, new TagDependency('item_42')))->toBe(13);
    expect(DependencyCache::rememberForever('item_42_total', static fn () => 13, new TagDependency('item_42')))->toBe(26);

    TagDependency::invalidate('item_42');

    expect(DependencyCache::rememberForever('item_42_price', static fn () => null, new TagDependency('item_42')))->toBeNull();
    expect(DependencyCache::rememberForever('item_42_total', static fn () => null, new TagDependency('item_42')))->toBeNull();
});

test('empty tags', function () {
    $dependency = new TagDependency([]);

    DependencyCache::rememberForever('item_42_price', static fn () => 13, $dependency);

    expect(DependencyCache::rememberForever('item_42_price', static fn () => 14))->toBe(13);
    expect(getInaccessibleProperty($dependency, 'data'))->toBe([]);
});

test('invalid ttl', function () {
    $this->expectException(InvalidArgumentException::class);

    new TagDependency('test', 0);
});

test('invalid tag', function () {
    $dependency = new TagDependency("\xB1\x31");

    $this->expectException(InvalidArgumentException::class);

    $dependency->evaluate(DependencyCache::store());
});
