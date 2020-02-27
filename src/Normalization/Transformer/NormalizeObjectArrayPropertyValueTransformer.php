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
        if (!\is_array($value)) {
            throw NormalizationException::CannotNormalizeNonObjectToClass($context, 'array', $this->className);
        }

        $ret = [];
        foreach ($value as $key => $v) {
            $ctx = new NormalizationContext($context->getPropertyName(), $v, $context->getObject(), $context->getNormalizer());
            $ret[$key] = parent::transform($ctx);
        }

        return $ret;
    }
}
