<?php

namespace Morebec\DomSer\Normalization\Configuration;

class ObjectNormalizationDefinitionFactory extends ObjectNormalizationDefinition
{
    private function __construct(string $className)
    {
        parent::__construct($className);
    }

    public static function forClass(string $className, \Closure $closure): NormalizationDefinition
    {
        $def = new static($className);
        $closure($def);

        return $def;
    }
}
