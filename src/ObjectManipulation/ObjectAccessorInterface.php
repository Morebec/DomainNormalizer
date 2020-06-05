<?php

namespace Morebec\DomainNormalizer\ObjectManipulation;

interface ObjectAccessorInterface
{
    /**
     * Returns a new accessor instance for a given object.
     *
     * @return static
     */
    public static function access(object $object): self;

    /**
     * Reads the property of an object.
     *
     * @return mixed
     */
    public function readProperty(string $propertyName);

    /**
     * Writes the property of an object.
     *
     * @param mixed $value
     */
    public function writeProperty(string $propertyName, $value): void;

    /**
     * Indicates if the object inspected by this accessor
     * has a certain property.
     */
    public function hasProperty(string $propertyName): bool;

    /**
     * Returns the list of all property names.
     */
    public function getProperties(): array;
}
