# DomainNormalizer
DomainNormalizer is an Orkestra Component used to easily normalize domain objects, through a fluent interface. 
It has the benefit of moving serialization/persistence concerns out of the domain classes, while providing a 
quick and easy way of defining primitive mapping information for domain classes.

It contains features for both serialization and deserialization (through normalization).

## Normalization
Normalization is the process of transforming a Domain Object instance to an array representation of primitive (int,string,float.bool, array) values.
This array representation is called a normalized form.

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
use Morebec\DomainNormalizer\Normalization\Configuration\NormalizerConfiguration;
use Morebec\DomainNormalizer\Normalization\Configuration\ObjectNormalizationDefinitionFactory as DefinitionFactory;
use Morebec\DomainNormalizer\Normalization\Configuration\ObjectNormalizationDefinition as Definition;
use Morebec\DomainNormalizer\Normalization\Normalizer;
use Morebec\DomainNormalizer\Normalization\NormalizationContext;

$config = new NormalizerConfiguration();


$config->registerDefinition(DefinitionFactory::forClass(
    Order::class,
    static function (Definition $d) {
        $d->property('id')
            ->renamedTo('ID')
            ->asString();

        $d->property('createdAt')->as(static function (NormalizationContext $context) {
            $value = $context->getValue();
            return (new DateTime("@$value"))->format('Y-m-d');
        });

        $d->property('lineItems')
            ->asArrayOfTransformed(OrderLineItem::class);

        $d->createProperty('nbLineItems')
            ->as(static function(NormalizationContext $context) {
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

Would return the following normalized form:

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
use Morebec\DomainNormalizer\Normalization\Configuration\ObjectNormalizationDefinition;

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

The rules are simple:
- If the normalizer cannot find a definition for a given object:
    - It will look in its registry to find out if there is a definition for one of the object's parent class that it can use.
    - Otherwise: It will throw an exception
- Definitions follow an explicit declaration approach:
    - If a property exists on the instance but is not part the definition, it will not be normalized. It will be ignored.
    - If a property exists does not exists on the instance but is part of the definition:
        - Unless it is a "bound" property, the normalizer will throw an exception.
        - In the case of Bound properties, they will be added to the resulting normalized form.
            (This can be defined either though the `bound` definition when using definition factory or through the `createProperty`)

### Denormalization
The process of denormalization is the opposite of normalization: Transforming a normalized form
to a Domain Object Instance.

In denormalization definitions, instead of defining properties, we define keys.

The rules are similar to normalization but presets some subtle differences:
- Definitions also follow an explicit declaration approach:
    - If key exists on the normalized form but it not part of the definition, it will not be denormalized.
    - Every nested normalized forms must have an associated definition.
        - If none is found, the denormalizer will throw an exception.
    - If a key definition exists but the associated data does not:
        - It will apply the configured missing transformer which is usually to either throw an error, or provide a default value.
           - By default it is to throw an error
    - If a key definition exists, but no corresponding property exists on the class:   
        - Throw an exception.
    
```php
class OrderDenormalizationDefinition extends ObjectDenormalizationDefinition
{
    public function __construct()
    {
        parent::_construct(Order::class);
        $this->key('ID')
             ->renamedTo('id')
             ->as(static function (TransformationContext $context) {
                return new ProductId($context->getValue());  
            }
        );
        
        $this->key('createdAt')->as(static function (TransformationContext $context) {
            return strtotime($context->getValue());
        });
        $this->key('newKey')->defaultsTo('test');

        $this->key('lineItems')
            ->asArrayOfTransformed(OrderLineItem::class);

    }
}
```

