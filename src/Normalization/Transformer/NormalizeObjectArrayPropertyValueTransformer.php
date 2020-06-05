<?php

namespace Morebec\DomainNormalizer\Normalization\Transformer;

use Morebec\DomainNormalizer\Normalization\Exception\NormalizationException;
use Morebec\DomainNormalizer\Normalization\NormalizationContext;

class NormalizeObjectArrayPropertyValueTransformer extends NormalizeObjectPropertyValueTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(NormalizationContext $context)
    {
        $value = $context->getValue();
        if (!\is_array($value) && !is_iterable($value)) {
            $valueType = \gettype($value);
            $displayType = $valueType === 'object' ? \get_class($value) : $valueType;
            throw NormalizationException::CannotNormalizeNonObjectToClass($context, $displayType, 'iterable');
        }

        $ret = [];
        foreach ($value as $key => $v) {
            $ctx = new NormalizationContext($context->getPropertyName(), $v, $context->getObject(), $context->getNormalizer());
            $ret[$key] = parent::transform($ctx);
        }

        return $ret;
    }
}
