<?php


namespace Tests\Morebec\DomainNormalizer\TestClasses;

class TestObject {

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }
}