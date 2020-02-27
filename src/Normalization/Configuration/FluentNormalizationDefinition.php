<?php

namespace Morebec\DomSer\Normalization\Configuration;

class FluentNormalizationDefinition extends NormalizationDefinition
{
    public static function forClass(string $className): self
    {
        return new self($className);
    }

    /**
     * Method used to specify the a property to normalize.
     */
    public function property(string $propertyName): FluentNormalizedPropertyDefinition
    {
        $propertyDefinition = new FluentNormalizedPropertyDefinition($this, $propertyName);
        $this->properties[$propertyName] = $propertyDefinition;

        return $propertyDefinition;
    }
}
