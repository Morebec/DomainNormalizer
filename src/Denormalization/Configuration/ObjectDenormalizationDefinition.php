<?php

namespace Morebec\DomainNormalizer\Denormalization\Configuration;

class ObjectDenormalizationDefinition extends DenormalizationDefinition
{
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
