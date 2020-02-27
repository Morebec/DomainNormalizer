<?php

namespace Morebec\DomSer\Denormalization\Transformer;

use Morebec\DomSer\Denormalization\DenormalizationContext;
use Morebec\DomSer\Denormalization\Exception\DenormalizationException;

class DenormalizeKeyAsObjectArrayTransformer extends DenormalizeKeyAsObjectTransformer
{
    public function transform(DenormalizationContext $context)
    {
        $value = $context->getValue();
        if (!\is_array($value)) {
            throw DenormalizationException::CannotDenormalizeNonObjectToClass($context, 'array', $this->className);
        }

        $ret = [];
        foreach ($value as $key => $v) {
            $ctx = new DenormalizationContext(
                $context->getKeyName(),
                $context->getDenormalizedKeyName(),
                $v,
                $context->getObject(),
                $context->getDenormalizer()
            );
            $ret[$key] = parent::transform($ctx);
        }

        return $ret;
    }
}
