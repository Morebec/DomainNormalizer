<?php


namespace Morebec\DomSer\Normalization\Transformer;


class LowerCaseStringTransformer extends StringTransformer
{
    public function transform(TransformationContext $context)
    {
        $value = parent::transform($context);
        return $value ? strtolower($value) : null;
    }
}