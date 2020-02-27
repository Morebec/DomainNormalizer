<?php

namespace Morebec\DomainNormalizer\Denormalization\Transformer;

use Morebec\DomainNormalizer\Denormalization\DenormalizationContext;
use Morebec\DomainNormalizer\Denormalization\Exception\DenormalizationException;
use Morebec\DomainNormalizer\ValueTransformer\ClosureValueTransformer;

/**
 * Unlike using a DenormalizationValueTransformer with a ClosureValueTransformer
 * this specified DenormalizationValueTransformer passes the denormalization context to the provided closure instead of
 * simply the value.
 */
class DenormalizationClosureValueTransformer implements KeyValueTransformerInterface
{
    /**
     * @var ClosureValueTransformer
     */
    private $valueTransformer;

    public function __construct(\Closure $closure)
    {
        $this->valueTransformer = new ClosureValueTransformer($closure);
    }

    public function transform(DenormalizationContext $context)
    {
        try {
            return $this->valueTransformer->transform($context);
        } catch (\Exception $exception) {
            throw new DenormalizationException("Could not denormalize key '{$context->getKeyName()}': {$exception->getMessage()}", $exception->getCode(), $exception->getPrevious());
        }
    }
}
