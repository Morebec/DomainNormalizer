<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Hydrator\HydrationContext;

class NullHydratorTypeTransformer implements HydratorTypeTransformerInterface
{
    public const TYPE_NAME = 'null';

    public function transform(HydrationContext $context)
    {
        return null;
    }

    public function getTypeName(): string
    {
        return self::TYPE_NAME;
    }
}
