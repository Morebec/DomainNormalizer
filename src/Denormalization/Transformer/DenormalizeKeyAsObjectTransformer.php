<?php

namespace Morebec\DomainNormalizer\Denormalization\Transformer;

use Morebec\DomainNormalizer\Denormalization\DenormalizationContext;
use Morebec\DomainNormalizer\Denormalization\Exception\DenormalizationException;

class DenormalizeKeyAsObjectTransformer implements KeyValueTransformerInterface
{
    /**
     * @var string
     */
    protected $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function transform(DenormalizationContext $context)
    {
        $value = $context->getValue();

        if (!\is_array($value)) {
            $type = \gettype($value);
            throw DenormalizationException::UnexpectedKeyValueType($context, $type, 'array');
        }

        return $context->getDenormalizer()->denormalize($value, $this->className);
    }
}
