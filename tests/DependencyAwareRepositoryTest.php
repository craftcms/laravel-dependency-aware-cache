<?php

use CraftCms\DependencyAwareCache\Dependency\TagDependency;
use CraftCms\DependencyAwareCache\Facades\DependencyCache;

test('get with dependency', function () {
    DependencyCache::put('foo', 'value', dependency: new TagDependency('tag'));

    expect(DependencyCache::get('foo'))->toBe('value');

    TagDependency::invalidate('tag');

    expect(DependencyCache::get('foo'))->toBeNull();
});

test('many with dependency', function () {
    DependencyCache::put('foo', 'value', dependency: new TagDependency('tag'));
    DependencyCache::put('bar', 'value', dependency: new TagDependency('tag'));

    expect(DependencyCache::many(['foo', 'bar']))->toBe([
        'foo' => 'value',
        'bar' => 'value',
    ]);

    TagDependency::invalidate('tag');

    expect(DependencyCache::many(['foo', 'bar']))->toBe([
        'foo' => null,
        'bar' => null,
    ]);
});

test('set with dependency', function () {
    DependencyCache::set('foo', 'value', dependency: new TagDependency('tag'));

    expect(DependencyCache::get('foo'))->toBe('value');
});

test('add with dependency', function () {
    DependencyCache::add('foo', 'value', dependency: new TagDependency('tag'));

    expect(DependencyCache::get('foo'))->toBe('value');
});

test('forever with dependency', function () {
    DependencyCache::forever('foo', 'value', new TagDependency('tag'));

    expect(DependencyCache::get('foo'))->toBe('value');
});

test('remember with dependency', function () {
    DependencyCache::remember('foo', 20, fn () => 'value', new TagDependency('tag'));

    expect(DependencyCache::get('foo'))->toBe('value');
});

test('sear with dependency', function () {
    DependencyCache::sear('foo', fn () => 'value', new TagDependency('tag'));

    expect(DependencyCache::get('foo'))->toBe('value');
});

test('rememberForever with dependency', function () {
    DependencyCache::rememberForever('foo', fn () => 'value', new TagDependency('tag'));

    expect(DependencyCache::get('foo'))->toBe('value');
});
