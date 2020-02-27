<?php


namespace Morebec\DomSer\Normalization\Transformer;

/**
 * Represents something that needs to be transformed.
 * It is a way to provide context information to field value transformers
 */
class TransformationContext
{
    /** @var string */
    private $fieldName;

    /** @var mixed */
    private $value;

    /** @var object */
    private $object;

    public function __construct(string $fieldName, $value, object $object)
    {
        $this->fieldName = $fieldName;
        $this->value = $value;
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return object
     */
    public function getObject(): object
    {
        return $this->object;
    }
}