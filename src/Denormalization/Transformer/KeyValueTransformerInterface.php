<?php

namespace Morebec\DomainNormalizer\Denormalization\Transformer;

use Morebec\DomainNormalizer\Denormalization\DenormalizationContext;

interface KeyValueTransformerInterface
{
    /**
     * Transforms the value of a given property of an object.
     *
     * @return mixed
     */
    public function transform(DenormalizationContext $context);
}
