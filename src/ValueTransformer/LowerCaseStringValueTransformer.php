<?php

namespace Morebec\DomainNormalizer\ValueTransformer;

class LowerCaseStringValueTransformer extends StringValueTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        $value = parent::transform($value);

        return $value ? strtolower($value) : null;
    }
}
