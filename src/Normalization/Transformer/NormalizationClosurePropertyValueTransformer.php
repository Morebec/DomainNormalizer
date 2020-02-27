<?php

namespace Morebec\DomainNormalizer\Normalization\Transformer;

use Morebec\DomainNormalizer\Normalization\Exception\NormalizationException;
use Morebec\DomainNormalizer\Normalization\NormalizationContext;
use Morebec\DomainNormalizer\ValueTransformer\ClosureValueTransformer;

class NormalizationClosurePropertyValueTransformer implements PropertyValueTransformerInterface
{
    /**
     * @var ClosureValueTransformer
     */
    private $valueTransformer;

    public function __construct(\Closure $closure)
    {
        $this->valueTransformer = new ClosureValueTransformer($closure);
    }

    public function transform(NormalizationContext $context)
    {
        try {
            return $this->valueTransformer->transform($context);
        } catch (\Exception $exception) {
            throw new NormalizationException("Could not normalize property '{$context->getPropertyName()}': {$exception->getMessage()}", $exception->getCode(), $exception->getPrevious());
        }
    }
}
