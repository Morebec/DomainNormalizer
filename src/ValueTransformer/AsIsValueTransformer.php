<?php

namespace Morebec\DomSer\ValueTransformer;

/**
 * Does not change the value.
 */
class AsIsValueTransformer implements ValueTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return $value;
    }
}
