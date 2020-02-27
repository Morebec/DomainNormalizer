<?php

namespace Morebec\DomSer\Normalization;

final class NormalizationDefinition
{
    /**
     * @var string name of the class to normalize
     */
    private $className;

    /** @var array<NormalizedPropertyDefinition> */
    private $properties;

    public function __construct(string $className)
    {
        $this->className = $className;
        $this->properties = [];
    }

    public static function forClass(string $className): self
    {
        return new self($className);
    }

    /**
     * Method used to specify the a property to normalize.
     */
    public function property(string $propertyName): NormalizedPropertyDefinition
    {
        $propertyDefinition = new NormalizedPropertyDefinition($this, $propertyName);
        $this->properties[$propertyName] = $propertyDefinition;

        return $propertyDefinition;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return array<NormalizedPropertyDefinition>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
