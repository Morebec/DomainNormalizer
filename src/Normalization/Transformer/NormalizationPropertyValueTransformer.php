<?php

namespace Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Exception\NormalizationException;
use Morebec\DomSer\Normalization\NormalizationContext;
use Morebec\DomSer\ValueTransformer\ValueTransformerInterface;

class NormalizationPropertyValueTransformer implements PropertyValueTransformerInterface
{
    /**
     * @var ValueTransformerInterface
     */
    private $valueTransformer;

    public function __construct(ValueTransformerInterface $valueTransformer)
    {
        $this->valueTransformer = $valueTransformer;
    }

    public function transform(NormalizationContext $context)
    {
        try {
            return $this->valueTransformer->transform($context->getValue());
        } catch (\Exception $e) {
            throw new NormalizationException(sprintf("Could not normalize property '%s' of class %s: %s", $context->getPropertyName(), $context->getObjectClassName(), $e->getMessage()), $e->getCode(), $e->getPrevious());
        }
    }
}
