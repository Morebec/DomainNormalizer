<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Hydrator\HydrationContext;

interface HydratorTypeTransformerInterface
{
    /**
     * Transforms a value v.
     *
     * @return mixed
     */
    public function transform(HydrationContext $context);

    /**
     * Returns the name of the type that is transformable.
     */
    public function getTypeName(): string;
}
