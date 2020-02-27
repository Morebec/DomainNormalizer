<?php


namespace Tests\Morebec\DomSer\Normalization;


class TestOrderLineItem
{
    /**
     * @var string
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

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}