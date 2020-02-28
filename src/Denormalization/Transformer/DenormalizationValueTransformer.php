<?php

namespace Morebec\DomainNormalizer\Denormalization\Transformer;

use Morebec\DomainNormalizer\Denormalization\DenormalizationContext;
use Morebec\DomainNormalizer\Denormalization\Exception\DenormalizationException;
use Morebec\DomainNormalizer\ValueTransformer\ValueTransformerInterface;

/**
 * Implementation of a KeyValueTransformer that relies on Value Transformers.
 */
class DenormalizationValueTransformer implements KeyValueTransformerInterface
{
    /**
     * @var ValueTransformerInterface
     */
    private $valueTransformer;

    public function __construct(ValueTransformerInterface $valueTransformer)
    {
        $this->valueTransformer = $valueTransformer;
    }

    public function transform(DenormalizationContext $context)
    {
        try {
            return $this->valueTransformer->transform($context->getValue());
        } catch (\Exception $exception) {
            throw new DenormalizationException("Could not denormalize key '{$context->getKeyName()}': {$exception->getMessage()}");
        }
    }
}
