<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Hydrator\HydrationContext;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorInterface;

interface HydratorTypeTransformerInterface
{
    /**
     * Transforms a value v.
     *
     * @param string $className
     * @param $data
     * @param HydratorInterface $hydrator
     *
     * @return mixed
     */
    public function transform(HydrationContext $context);

    /**
     * Returns the name of the type that is transformable.
     */
    public function getTypeName(): string;
}
