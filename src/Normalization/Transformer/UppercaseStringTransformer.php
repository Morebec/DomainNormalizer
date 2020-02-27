<?php


namespace Morebec\DomSer\Normalization\Transformer;


class UppercaseStringTransformer extends StringTransformer
{
    public function transform(TransformationContext $context)
    {
        $value = parent::transform($context);
        return $value ? strtoupper($value) : null;
    }
}