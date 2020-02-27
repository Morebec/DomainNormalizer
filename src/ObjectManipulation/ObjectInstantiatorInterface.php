<?php

namespace Morebec\DomSer\ObjectManipulation;

interface ObjectInstantiatorInterface
{
    /**
     * Instantiates an object.
     */
    public function instantiate(string $className): object;
}
