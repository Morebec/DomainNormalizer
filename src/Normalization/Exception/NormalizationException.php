<?php

namespace Morebec\DomSer\Normalization\Exception;

use Morebec\DomSer\Normalization\NormalizationContext;

class NormalizationException extends \RuntimeException
{
    public static function ClassDefinitionNotFound(string $class): self
    {
        return new self("No definition found for class {$class}");
    }

    public static function ClassPropertyNotFound(string $objectClass, string $propertyName): self
    {
        return new self("Property '{$propertyName}' not found for class {$objectClass}");
    }

    /**
     * Exception thrown when a property value was expected to be an object to be normalized as a given class.
     *
     * @param string $className
     * @param string $propertyName
     *
     * @return NormalizationException
     */
    public static function CannotNormalizeNonObjectToClass(
        NormalizationContext $context,
        string $unexpectedValueType,
        string $expectedClassName
    ): self {
        return self::UnexpectedPropertyValueClass($context, $unexpectedValueType, $expectedClassName);
    }

    /**
     * Thrown when a property value was expected to be of a given class.
     *
     * @param string $className
     * @param string $propertyName
     *
     * @return NormalizationException
     */
    public static function UnexpectedPropertyValueClass(
        NormalizationContext $context,
        string $unexpectedClassName,
        string $expectedClassName
    ): self {
        return new self(
            sprintf(
                "Cannot Normalize property '%s' of class %s: Expected value to be of type %s, got %s",
                $context->getPropertyName(),
                $context->getObjectClassName(),
                $expectedClassName,
                $unexpectedClassName
            )
        );
    }
}
