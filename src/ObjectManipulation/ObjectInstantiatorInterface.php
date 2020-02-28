<?php

namespace Morebec\DomainNormalizer\ObjectManipulation;

interface ObjectInstantiatorInterface
{
    /**
     * Instantiates an object.
     */
    public function instantiate(string $className): object;
}
