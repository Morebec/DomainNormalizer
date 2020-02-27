<?php

namespace Morebec\DomainNormalizer\Normalization\Transformer;

use Morebec\DomainNormalizer\Normalization\NormalizationContext;

/**
 * A property transformation represents the transformation to execute for the value of a given property.
 */
interface PropertyValueTransformerInterface
{
    /**
     * Transforms the value of a given property of an object.
     *
     * @return mixed
     */
    public function transform(NormalizationContext $context);
}
