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

Using the following definition:

```php
use DateTime;
use Morebec\DomSer\Normalization\Configuration\NormalizerConfiguration;
use Morebec\DomSer\Normalization\Configuration\ObjectDefinitionFactory as DefinitionFactory;
use Morebec\DomSer\Normalization\Configuration\ObjectNormalizationDefinition as Definition;
use Morebec\DomSer\Normalization\Normalizer;
use Morebec\DomSer\Normalization\Transformer\TransformationContext;

$config = new NormalizerConfiguration();


$config->registerDefinition(DefinitionFactory::forClass(
    Order::class,
    static function (Definition $d) {
        $d->property('id')
            ->renamedTo('ID')
            ->asString();

        $d->property('createdAt')->as(static function (TransformationContext $context) {
            $value = $context->getValue();
            return (new DateTime("@$value"))->format('Y-m-d');
        });

        $d->property('lineItems')
            ->asArrayOfTransformed(OrderLineItem::class);

        $d->createProperty('nbLineItems')
            ->as(static function(TransformationContext $context) {
                return count($context->getObject()->getLineItems());
            }
        );
    })
);


$config->registerDefinition(DefinitionFactory::forClass(
    OrderLineItem::class,
    static function (Definition $d) {
        $d->property('quantity');
        $d->property('productId')->asString();
    })
);

$normalizer = new Normalizer($config);
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


It is also possible to contain your definitions inside classes:

```php
use Morebec\DomSer\Normalization\Configuration\ObjectNormalizationDefinition;

class OrderDefinition extends ObjectNormalizationDefinition 
{
    public function __construct() 
    {
        parent::__construct(Order::class);
        
        $this->property('id')
            ->renamedTo('ID')
            ->asString();

        $this->property('createdAt')->as(static function (TransformationContext $context) {
            $value = $context->getValue();
            return (new DateTime("@$value"))->format('Y-m-d');
        });

        $this->property('lineItems')
            ->asArrayOfTransformed(OrderLineItem::class);

        $this->createProperty('nbLineItems')
            ->as(static function(TransformationContext $context) {
                return count($context->getObject()->getLineItems());
            }
        );
    }
}
```