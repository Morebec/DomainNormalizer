<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator;

use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer\HydratorTypeTransformerInterface;

/**
 * Hydrators are responsible for converting an array back to object instances.
 */
interface HydratorInterface
{
    /**
     * Hydrates a new instance of an object using data.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public function hydrate(string $className, $data);

    /**
     * Register a custom hydrator.
     */
    public function registerTransformer(HydratorTypeTransformerInterface $transformer);
}
