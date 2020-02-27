<?php

namespace Morebec\DomSer\Denormalization\Transformer;

use Morebec\DomSer\Denormalization\DenormalizationContext;

interface KeyValueTransformerInterface
{
    /**
     * Transforms the value of a given property of an object.
     *
     * @return mixed
     */
    public function transform(DenormalizationContext $context);
}
