<?php

namespace Morebec\DomSer\Denormalization\Transformer;

use Morebec\DomSer\Denormalization\DenormalizationContext;
use Morebec\DomSer\Denormalization\Exception\DenormalizationException;
use Morebec\DomSer\ValueTransformer\ValueTransformerInterface;

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
