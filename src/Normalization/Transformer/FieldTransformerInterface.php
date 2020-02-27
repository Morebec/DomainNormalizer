<?php


namespace Morebec\DomSer\Normalization\Transformer;

/**
 * A Field transformation represents the transformation to execute for the value of a given field
 */
interface FieldTransformerInterface
{
    /**
     * Transforms the value of a given field of an object
     * @param TransformationContext $context
     * @return mixed
     */
    public function transform(TransformationContext $context);
}