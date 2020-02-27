<?php


namespace Tests\Morebec\DomSer\Normalization;


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
}