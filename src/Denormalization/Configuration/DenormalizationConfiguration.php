<?php

namespace Morebec\DomainNormalizer\Denormalization\Configuration;

class DenormalizationConfiguration
{
    /** @var array<DenormalizationDefinition> */
    private $definitions;

    public function __construct()
    {
        $this->definitions = [];
    }

    /**
     * Returns the definition associated with an class.
     */
    public function getDefinitionForClass(string $className): ?DenormalizationDefinition
    {
        if (!\array_key_exists($className, $this->definitions)) {
            return null;
        }

        return $this->definitions[$className];
    }

    /**
     * Registers a new definition with this configuration.
     * If a definition already exists for a class, this overwrites it.
     *
     * @return $this
     */
    public function registerDefinition(DenormalizationDefinition $definition): self
    {
        $this->definitions[$definition->getClassName()] = $definition;

        return $this;
    }
}
