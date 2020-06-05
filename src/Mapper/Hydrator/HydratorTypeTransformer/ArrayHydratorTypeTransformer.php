<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Hydrator\HydrationContext;

class ArrayHydratorTypeTransformer implements HydratorTypeTransformerInterface
{
    public const TYPE_NAME = 'array';

    public function transform(HydrationContext $context)
    {
        return array_map(static function ($d) use ($context) {
            $className = $context->getClassName();
            $itemType = str_replace('[]', '', $className);
            if ($className === 'array') {
                // We have a simple array without any type definition we'll use scalar as a type
                $itemType = 'scalar';
            }

            return $context->getHydrator()->hydrate($itemType, $d);
        }, $context->getData());
    }

    public function getTypeName(): string
    {
        return self::TYPE_NAME;
    }
}
