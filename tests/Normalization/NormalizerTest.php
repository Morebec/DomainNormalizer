<?php

namespace Tests\Morebec\DomSer\Normalization;

use DateTime;
use Morebec\DomSer\Normalization\NormalizationDefinition as Definition;
use Morebec\DomSer\Normalization\Configuration\NormalizerConfiguration;
use Morebec\DomSer\Normalization\Normalizer;
use Morebec\DomSer\Normalization\Transformer\TransformationContext;
use PHPUnit\Framework\TestCase;

class NormalizerTest extends TestCase
{

    public function testNormalize(): void
    {

        $config = new NormalizerConfiguration();

        $config->registerDefinition(Definition::forClass(TestOrder::class)
                        ->property('id')->asString()
                        ->property('createdAt')->as(static function (TransformationContext $context) {
                            $value = $context->getValue();
                            return (new DateTime("@$value"))->format('Y-m-d');
                        })
                        ->property('lineItems')->asArrayOfTransformed(TestOrderLineItem::class)
        );

        $config->registerDefinition(Definition::forClass(TestOrderLineItem::class)
                        ->property('quantity')
                        ->property('productId')->asString()
        );

        $normalizer = new Normalizer($config);

        $order = new TestOrder();
        $data = $normalizer->normalize($order);

        $expected = [
            'id' => $order->getId(),
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
            ]
        ];


        $this->assertEquals($expected, $data);
    }
}
