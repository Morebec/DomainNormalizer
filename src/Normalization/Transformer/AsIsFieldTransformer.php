<?php


namespace Morebec\DomSer\Normalization\Transformer;

/**
 * Does not change the value
 */
class AsIsFieldTransformer implements FieldTransformerInterface
{

    public function transform(TransformationContext $context)
    {
        return $context->getValue();
    }
}