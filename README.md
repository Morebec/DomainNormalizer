# DomSer
DomSer (Short for Domain Serializer) is an Orkestra Component used to easily serialize domain objects, 
through a fluent interface. It has the benefit of moving serialization/persistence concerns out of the
domain classes, while providing a quick and easy way of defining primitive mapping information for domain classes.

It contains features for both serialization and deserialization (through normalization).

## Normalization Example
Given a class structure like:
```php
class Order
{
    /** @var string */
    private $id;

    /** @var int */
    private $createdAt;

    /** @var array<OrderLineItem> */
    private $lineItems;
}


class OrderLineItem
{
    /**
     * @var string
     */
    private $productId;
    /**
     * @var int
     */
    private $quantity;

    public function __construct(ProductId $productId, int $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
}

class ProductId
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
``` 

Using the following MappignL

```php
$definitions[] = NormalizationDefinition::forClass(Order::class)
                ->field('id')->asString()
                ->field('createdAt')->mappedAs(static function ($value) {
                    return (new \DateTime("@$value"))->format('Y-m-d');
                })
                ->field('lineItems')->asMappedArrayOf(OrderLineItem::class)
;

$definitions[] = NormalizationDefinition::forClass(OrderLineItem::class)
                ->field('quantity')
                ->field('productId')->asString()
;

$normalizer = new Normalizer($definitions);

$obj = new Order();
$data = $normalizer->normalize($obj);
```

Would return the following normalized map:

```php
[
  "id" => "id5e5716cf048284.16614551",
  "createdAt" => "2020-02-27",
  "lineItems" => [
    0 => [
      "quantity" => 5,
      "productId" => "5e5716cf0485b7.29093456",
    ],
    1 => [
      "quantity" => 5,
      "productId" => "5e5716cf048606.07838602"
    ]
  ]
];
```