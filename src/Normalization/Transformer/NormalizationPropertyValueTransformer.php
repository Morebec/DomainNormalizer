<?php

namespace Morebec\DomainNormalizer\Normalization\Transformer;

use Morebec\DomainNormalizer\Normalization\Exception\NormalizationException;
use Morebec\DomainNormalizer\Normalization\NormalizationContext;
use Morebec\DomainNormalizer\ValueTransformer\ValueTransformerInterface;

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
