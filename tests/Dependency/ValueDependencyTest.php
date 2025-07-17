<?php

use Craft\DependencyAwareCache\Dependency\ValueDependency;
use Craft\DependencyAwareCache\Facades\DependencyCache;

it('matches value', function ($value) {
    $dependency = new ValueDependency($value);

    setInaccessibleProperty($dependency, 'data', $value);
    expect($dependency->isChanged(DependencyCache::store()))->toBeFalse();

    setInaccessibleProperty($dependency, 'data', false);
    expect($dependency->isChanged(DependencyCache::store()))->toBeTrue();
})->with([
    'int' => [1],
    'float' => [1.1],
    'string' => ['a'],
    'array' => [[]],
    'bool' => [true],
    'null' => [null],
    'callable' => [fn () => null],
    'object' => [new stdClass],
]);
