<?php


namespace Tests\Morebec\DomainNormalizer\TestClasses;


class TestId implements TestStringifiable
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return $this->id;
    }
}