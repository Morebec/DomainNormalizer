<?php

namespace Morebec\DomSer\Normalization;

/**
 * Represents something that needs to be transformed during normalization.
 * It is a way to provide context information to property value transformers.
 */
class NormalizationContext
{
    /** @var string */
    private $propertyName;

    /** @var mixed */
    private $value;

    /** @var object */
    private $object;
    /**
     * @var Normalizer
     */
    private $normalizer;

    public function __construct(string $propertyName, $value, object $object, Normalizer $normalizer)
    {
        $this->propertyName = $propertyName;
        $this->value = $value;
        $this->object = $object;
        $this->normalizer = $normalizer;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getObject(): object
    {
        return $this->object;
    }

    public function getNormalizer(): Normalizer
    {
        return $this->normalizer;
    }

    public function getObjectClassName(): string
    {
        return \get_class($this->object);
    }
}
