<?php

namespace Morebec\DomainNormalizer\Denormalization\Configuration;

class ObjectDenormalizationDefinitionFactory extends ObjectDenormalizationDefinition
{
    private function __construct(string $className)
    {
        parent::__construct($className);
    }

    public static function forClass(string $className, \Closure $closure): DenormalizationDefinition
    {
        $def = new static($className);
        $closure($def);

        return $def;
    }
}
