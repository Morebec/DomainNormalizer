<?php


namespace Tests\Morebec\DomainNormalizer\TestClasses;


class TestValueObject
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {

        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function fromString(string $v)
    {
        return new self($v);
    }
}