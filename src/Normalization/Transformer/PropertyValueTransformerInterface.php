<?php

namespace Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\NormalizationContext;

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
