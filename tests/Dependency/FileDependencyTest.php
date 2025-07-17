<?php

use CraftCms\DependencyAwareCache\Dependency\FileDependency;
use CraftCms\DependencyAwareCache\Facades\DependencyCache;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->filePath = dirname(__DIR__).'/runtime/file.txt';
    File::ensureDirectoryExists(dirname($this->filePath));
});

test('touching file marks dependency as changed', function () {
    touch($this->filePath);

    $dependency = new FileDependency($this->filePath);
    $dependency->evaluate(DependencyCache::store());

    expect($dependency->isChanged(DependencyCache::store()))->toBeFalse();

    touch($this->filePath, now()->addSecond()->timestamp);

    expect($dependency->isChanged(DependencyCache::store()))->toBeTrue();
});
