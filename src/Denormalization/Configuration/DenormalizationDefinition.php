<?php

namespace Morebec\DomainNormalizer\Denormalization\Configuration;

class DenormalizationDefinition
{
    /**
     * @var string name of the class to normalize
     */
    protected $className;

    /** @var array<DenormalizationKeyDefinition> */
    protected $keys;

    public function __construct(string $className)
    {
        if ($className === '') {
            throw new \InvalidArgumentException('The class name of a denormalization definition cannot be blank');
        }
        $this->className = $className;
        $this->keys = [];
    }

    /**
     * Returns the name of the class for which this definition applies.
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return array<DenormalizationKeyDefinition>
     */
    public function getKeyDefinitions(): array
    {
        return $this->keys;
    }
}
