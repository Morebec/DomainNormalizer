<?php

namespace Tests\Morebec\DomainNormalizer\TestClasses;

class TestOrder implements TestOrderInterface
{
    /** @var string */
    private $id;

    /** @var int */
    private $createdAt;

    /** @var array<TestOrderLineItem> */
    private $lineItems;

    public function __construct()
    {
        $this->id = uniqid('id', true);
        $this->createdAt = time();

        $this->lineItems = [
            new TestOrderLineItem(
                new TestProductId(uniqid('', true)),
                random_int(1, 5)
            ),
            new TestOrderLineItem(
                new TestProductId(uniqid('', true)),
                random_int(1, 5)
            ),
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function getLineItems(): array
    {
        return $this->lineItems;
    }
}
