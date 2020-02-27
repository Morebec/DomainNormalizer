<?php

namespace Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Exception\NormalizationException;
use Morebec\DomSer\Normalization\NormalizationContext;

/**
 * Transformer normalizing an property value that has an object type.
 */
class NormalizeObjectPropertyValueTransformer implements PropertyValueTransformerInterface
{
    /**
     * @var string
     */
    protected $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(NormalizationContext $context)
    {
        $value = $context->getValue();

        if ($value === null) {
            return null;
        }

        if (!\is_object($value)) {
            $valueType = \gettype($value);
            throw NormalizationException::CannotNormalizeNonObjectToClass($context, $valueType, $this->className);
        }

        $valueClass = \get_class($value);

        if ($valueClass !== $this->className) {
            throw NormalizationException::UnexpectedPropertyValueClass($context, $valueClass, $this->className);
        }

        // Pass value to normalizer.
        return $context->getNormalizer()->normalize($value);
    }
}
