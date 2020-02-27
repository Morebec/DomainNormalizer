<?php

namespace Morebec\DomSer\Denormalization;

/**
 * Represents something that needs to be transformed during denormalization.
 * It is a way to provide context information to property value transformers.
 */
class DenormalizationContext
{
    /** @var string */
    private $keyName;

    /** @var mixed */
    private $value;

    /** @var object */
    private $object;
    /**
     * @var Denormalizer
     */
    private $denormalizer;
    /**
     * @var string
     */
    private $denormalizedKeyName;

    public function __construct(
        string $keyName,
        string $denormalizedKeyName,
        $value,
        object $object,
        Denormalizer $denormalizer
    ) {
        $this->keyName = $keyName;
        $this->denormalizedKeyName = $denormalizedKeyName;
        $this->value = $value;
        $this->object = $object;
        $this->objectClassName = \get_class($object);
        $this->denormalizer = $denormalizer;
    }

    public function getKeyName(): string
    {
        return $this->keyName;
    }

    public function getDenormalizedKeyName(): string
    {
        return $this->denormalizedKeyName;
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

    public function getDenormalizer(): Denormalizer
    {
        return $this->denormalizer;
    }

    public function getObjectClassName(): string
    {
        return $this->objectClassName;
    }
}
