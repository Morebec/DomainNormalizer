<?php

namespace Morebec\DomainNormalizer\Mapper\AnnotationReader;

class VarType
{
    private $value;

    /**
     * @var string[]
     */
    private $types;

    public function __construct(string $value)
    {
        $this->value = $value;
        $types = explode('|', $value);
        $this->types = $types;
    }

    public function isNullable(): bool
    {
        return \in_array('null', $this->types);
    }

    public function isCompound(): bool
    {
        return \count($this->types) > 1;
    }

    public function isArray(): bool
    {
        $length = \strlen('[]');
        if ($length == 0) {
            return true;
        }

        return (substr($this->value, -$length) === '[]') || $this->value === 'array';
    }

    public function isScalar(): bool
    {
        foreach ($this->types as $type) {
            if (!\in_array($type, ['string', 'bool', 'int', 'float'])) {
                return false;
            }
        }

        return true;
    }

    public function isObject(): bool
    {
        return !$this->isScalar();
    }

    /**
     * Returns the name of the type.
     * In the case of objects, returns the FQDN.
     */
    public function getTypeNames(): array
    {
        return $this->types;
    }
}
