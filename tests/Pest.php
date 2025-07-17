<?php

use Craft\DependencyAwareCache\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function setInaccessibleProperty(object|string $object, string $propertyName, mixed $value, bool $revoke = true): void
{
    $class = new ReflectionClass($object);

    while (! $class->hasProperty($propertyName)) {
        $class = $class->getParentClass();
    }

    $property = $class->getProperty($propertyName);
    $property->setAccessible(true);
    $property->setValue($object, $value);

    if ($revoke) {
        $property->setAccessible(false);
    }
}

function getInaccessibleProperty(object|string $object, string $propertyName, bool $revoke = true): mixed
{
    $class = new ReflectionClass($object);

    while (! $class->hasProperty($propertyName)) {
        $class = $class->getParentClass();
    }

    $property = $class->getProperty($propertyName);
    $property->setAccessible(true);
    $result = $property->getValue($object);

    if ($revoke) {
        $property->setAccessible(false);
    }

    return $result;
}
