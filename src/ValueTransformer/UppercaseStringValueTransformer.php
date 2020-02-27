<?php

namespace Morebec\DomSer\ValueTransformer;

class UppercaseStringValueTransformer extends StringValueTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        $value = parent::transform($value);

        return $value ? strtoupper($value) : null;
    }
}
