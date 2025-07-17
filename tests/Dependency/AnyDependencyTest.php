<?php

use Craft\DependencyAwareCache\Dependency\AnyDependency;
use Craft\DependencyAwareCache\Dependency\CallbackDependency;
use Craft\DependencyAwareCache\Facades\DependencyCache;

test('AnyDependenciesTest', function () {
    $data1 = new class
    {
        public int $data = 1;
    };

    $data2 = new class
    {
        public int $data = 2;
    };

    $dependency1 = new CallbackDependency(static fn () => $data1->data);

    $dependency2 = new CallbackDependency(static fn () => $data2->data);

    $anyDependency = new AnyDependency([$dependency1, $dependency2]);
    $anyDependency->evaluate(DependencyCache::store());

    expect($anyDependency->isChanged(DependencyCache::store()))->toBeFalse();

    $data2->data = 42;

    expect($anyDependency->isChanged(DependencyCache::store()))->toBeTrue();
});

test('arguments', function ($dependencies) {
    $this->expectException(InvalidArgumentException::class);

    new AnyDependency($dependencies);
})->with([
    'int' => [[1]],
    'float' => [[1.1]],
    'string' => [['a']],
    'array' => [[[]]],
    'bool' => [[true]],
    'null' => [[null]],
    'callable' => [[fn () => null]],
    'object' => [[new stdClass]],
]);
