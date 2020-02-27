<?php

namespace Morebec\DomSer\Normalization\Configuration;

class NormalizationDefinition
{
    /**
     * @var string name of the class to normalize
     */
    protected $className;

    /** @var array<NormalizedPropertyDefinition> */
    protected $properties;

    public function __construct(string $className)
    {
        $this->className = $className;
        $this->properties = [];
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
