<?php

namespace Morebec\DomainNormalizer\Denormalization\Configuration;

class FluentDenormalizationDefinition extends DenormalizationDefinition
{
    public static function forClass(string $className): self
    {
        return new self($className);
    }

    /**
     * Method used to specify the a property to normalize.
     */
    public function key(string $keyName): FluentDenormalizationKeyDefinition
    {
        $keyDefinition = new FluentDenormalizationKeyDefinition($this, $keyName);
        $this->keys[$keyName] = $keyDefinition;

        return $keyDefinition;
    }
}
