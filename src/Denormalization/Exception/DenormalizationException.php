<?php

namespace Morebec\DomainNormalizer\Denormalization\Exception;

use Morebec\DomainNormalizer\Denormalization\DenormalizationContext;

class DenormalizationException extends \RuntimeException
{
    public static function ClassDefinitionNotFound(string $class): self
    {
        return new self("No definition found for class {$class}");
    }

    public static function ClassKeyNotFound(string $objectClass, string $keyName): self
    {
        return new self("Key '{$keyName}' not found for class {$objectClass}");
    }

    /**
     * Exception thrown when a key value was expected to be an object to be denormalized as a given class.
     */
    public static function CannotDenormalizeNonObjectToClass(
        DenormalizationContext $context,
        string $unexpectedValueType,
        string $expectedClassName
    ): self {
        return self::UnexpectedKeyValueType($context, $unexpectedValueType, $expectedClassName);
    }

    /**
     * Thrown when a key value was expected to be of a given class.
     */
    public static function UnexpectedKeyValueType(
        DenormalizationContext $context,
        string $unexpectedTypeName,
        string $expectedTypeName
    ): self {
        return new self(
            sprintf(
                "Cannot denormalize key '%s': Expected value to be of type %s, got %s",
                $context->getKeyName(),
                $expectedTypeName,
                $unexpectedTypeName
            )
        );
    }

    /**
     * Thrown when a key was expected to be part of data.
     *
     * @return static
     */
    public static function MissingKeyInData(string $keyName): self
    {
        return new self("Cannot denormalize key '{$keyName}' as it was not found in the provided data");
    }
}
