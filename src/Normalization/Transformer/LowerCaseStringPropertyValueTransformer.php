<?php

namespace Morebec\DomSer\Normalization\Transformer;

class LowerCaseStringPropertyValueTransformer extends StringPropertyValueTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(TransformationContext $context)
    {
        $value = parent::transform($context);

        return $value ? strtolower($value) : null;
    }
}
