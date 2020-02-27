<?php

namespace Morebec\DomSer\Normalization\Transformer;

/**
 * Does not change the value.
 */
class AsIsPropertyValueTransformer implements PropertyValueTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(TransformationContext $context)
    {
        return $context->getValue();
    }
}
