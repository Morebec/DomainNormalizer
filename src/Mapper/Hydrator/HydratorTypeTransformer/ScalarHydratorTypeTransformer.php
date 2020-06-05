<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Hydrator\HydrationContext;

class ScalarHydratorTypeTransformer implements HydratorTypeTransformerInterface
{
    public const TYPE_NAME = 'scalar';

    public function transform(HydrationContext $context)
    {
        return $context->getData();
    }

    public function getTypeName(): string
    {
        return self::TYPE_NAME;
    }
}
