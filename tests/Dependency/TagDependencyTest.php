<?php

use CraftCms\DependencyAwareCache\Dependency\TagDependency;
use CraftCms\DependencyAwareCache\Events\TagsInvalidated;
use CraftCms\DependencyAwareCache\Facades\DependencyCache;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Support\Facades\Event;

test('invalidate by tag', function () {
    Event::fake();

    DependencyCache::rememberForever('item_42_price', static fn () => 13, new TagDependency('item_42'));
    DependencyCache::rememberForever('item_42_total', static fn () => 26, new TagDependency('item_42'));

    expect(DependencyCache::rememberForever('item_42_price', static fn () => 26, new TagDependency('item_42')))->toBe(13);
    expect(DependencyCache::rememberForever('item_42_total', static fn () => 13, new TagDependency('item_42')))->toBe(26);

    TagDependency::invalidate('item_42');

    expect(DependencyCache::rememberForever('item_42_price', static fn () => null, new TagDependency('item_42')))->toBeNull();
    expect(DependencyCache::rememberForever('item_42_total', static fn () => null, new TagDependency('item_42')))->toBeNull();

    Event::assertDispatched(TagsInvalidated::class, function (TagsInvalidated $event) {
        return $event->tags === 'item_42';
    });
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

test('an invalidation while tags are being read is not lost', function () {
    DependencyCache::forever('item_42_price', 13, new TagDependency('item_42'));

    // Invalidate the tag right after the next write reads it, as a concurrent request could
    Event::listen(CacheHit::class, function () {
        Event::forget(CacheHit::class);
        TagDependency::invalidate('item_42');
    });

    DependencyCache::forever('item_42_total', 26, new TagDependency('item_42'));

    expect(DependencyCache::get('item_42_price'))->toBeNull()
        ->and(DependencyCache::get('item_42_total'))->toBeNull();
});
