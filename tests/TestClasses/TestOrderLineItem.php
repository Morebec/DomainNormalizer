<?php

namespace Tests\Morebec\DomainNormalizer\TestClasses;

class TestOrderLineItem
{
    /**
     * @var TestProductId
     */
    private $productId;

    /**
     * @var int
     */
    private $quantity;

    public function __construct(TestProductId $productId, int $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
