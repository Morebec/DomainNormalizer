<?php

namespace Morebec\DomSer\ObjectManipulation;

use Doctrine\Instantiator\Instantiator;

class DoctrineInstantiator implements ObjectInstantiatorInterface
{
    public function instantiate(string $className): object
    {
        $instantiator = new Instantiator();

        return $instantiator->instantiate($className);
    }
}
