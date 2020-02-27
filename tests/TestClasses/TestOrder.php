<?php


namespace Tests\Morebec\DomSer\TestClasses;


use Tests\Morebec\DomSer\TestClasses\TestOrderLineItem;
use Tests\Morebec\DomSer\TestClasses\TestProductId;

class TestOrder
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
            )
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @return array
     */
    public function getLineItems(): array
    {
        return $this->lineItems;
    }
}