<?php

namespace Morebec\DomSer\Normalization\Transformer;

class UppercaseStringPropertyValueTransformer extends StringPropertyValueTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(TransformationContext $context)
    {
        $value = parent::transform($context);

        return $value ? strtoupper($value) : null;
    }
}
