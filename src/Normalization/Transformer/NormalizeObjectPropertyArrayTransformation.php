<?php

namespace Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Exception\NormalizationException;

class NormalizeObjectPropertyArrayTransformation extends NormalizeObjectPropertyValueTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(TransformationContext $context)
    {
        $value = $context->getValue();
        if (!\is_array($value)) {
            throw NormalizationException::CannotNormalizeNonObjectToClass($context, 'array', $this->className);
        }

        $ret = [];
        foreach ($value as $key => $v) {
            $ctx = new TransformationContext($context->getPropertyName(), $v, $context->getObject(), $context->getNormalizer());
            $ret[$key] = parent::transform($ctx);
        }

        return $ret;
    }
}
