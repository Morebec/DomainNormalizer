<?php

namespace Morebec\DomSer\Normalization\Configuration;

class ObjectNormalizationDefinition extends NormalizationDefinition
{
    /**
     * Method used to specify the a property to normalize.
     */
    public function property(string $propertyName): FluentNormalizedPropertyDefinition
    {
        $propertyDefinition = new FluentNormalizedPropertyDefinition($this, $propertyName);
        $this->properties[$propertyName] = $propertyDefinition;

        return $propertyDefinition;
    }

    /**
     * Creates an unbound property to add to the normalized form.
     */
    public function createProperty(string $propertyName): FluentNormalizedPropertyDefinition
    {
        return $this->property($propertyName)->unbound();
    }
}
