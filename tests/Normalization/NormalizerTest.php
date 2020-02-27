<?php

namespace Tests\Morebec\DomSer\Normalization;

use DateTime;
use Morebec\DomSer\Normalization\Configuration\NormalizerConfiguration;
use Morebec\DomSer\Normalization\Configuration\ObjectNormalizationDefinitionFactory as DefinitionFactory;
use Morebec\DomSer\Normalization\Configuration\ObjectNormalizationDefinition as Definition;
use Morebec\DomSer\Normalization\Normalizer;
use Morebec\DomSer\Normalization\NormalizationContext;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomSer\TestClasses\TestOrderLineItem;
use Tests\Morebec\DomSer\TestClasses\TestOrder;

class NormalizerTest extends TestCase
{

    public function testNormalize(): void
    {

        $config = new NormalizerConfiguration();


        $config->registerDefinition(DefinitionFactory::forClass(
            TestOrder::class,
            static function (Definition $d) {
                $d->property('id')
                    ->renamedTo('ID')
                    ->asString();

                $d->property('createdAt')->as(static function (NormalizationContext $context) {
                    $value = $context->getValue();
                    return (new DateTime("@$value"))->format('Y-m-d');
                });

                $d->property('lineItems')
                    ->asArrayOfTransformed(TestOrderLineItem::class);

                $d->createProperty('nbLineItems')
                    ->as(static function(NormalizationContext $context) {
                        return count($context->getObject()->getLineItems());
                    }
                );
            })
        );


        $config->registerDefinition(DefinitionFactory::forClass(
            TestOrderLineItem::class,
            static function (Definition $d) {
                $d->property('quantity');
                $d->property('productId')->asString();
            })
        );

        $normalizer = new Normalizer($config);

        $order = new TestOrder();
        $data = $normalizer->normalize($order);

        $expected = [
            'ID' => $order->getId(),
            'createdAt' => (new DateTime("@{$order->getCreatedAt()}"))->format('Y-m-d'),
            'lineItems' => [
                [
                    "quantity" => $order->getLineItems()[0]->getQuantity(),
                    "productId" => (string)$order->getLineItems()[0]->getProductId()
                ],
                [
                    "quantity" => $order->getLineItems()[1]->getQuantity(),
                    "productId" => (string)$order->getLineItems()[1]->getProductId()
                ]
            ],
            'nbLineItems' => 2
        ];


        $this->assertEquals($expected, $data);
    }
}
