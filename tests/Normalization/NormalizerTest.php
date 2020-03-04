<?php

namespace Tests\Morebec\DomainNormalizer\Normalization;

use DateTime;
use Morebec\DomainNormalizer\Normalization\Configuration\AutomaticNormalizationDefinition;
use Morebec\DomainNormalizer\Normalization\Configuration\NormalizerConfiguration;
use Morebec\DomainNormalizer\Normalization\Configuration\ObjectNormalizationDefinition as Definition;
use Morebec\DomainNormalizer\Normalization\Configuration\ObjectNormalizationDefinitionFactory as DefinitionFactory;
use Morebec\DomainNormalizer\Normalization\NormalizationContext;
use Morebec\DomainNormalizer\Normalization\Normalizer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestOrder;
use Tests\Morebec\DomainNormalizer\TestClasses\TestOrderLineItem;
use Tests\Morebec\DomainNormalizer\TestClasses\TestProductId;

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
                    ->as(static function (NormalizationContext $context) {
                        return \count($context->getObject()->getLineItems());
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
                    'quantity' => $order->getLineItems()[0]->getQuantity(),
                    'productId' => (string) $order->getLineItems()[0]->getProductId(),
                ],
                [
                    'quantity' => $order->getLineItems()[1]->getQuantity(),
                    'productId' => (string) $order->getLineItems()[1]->getProductId(),
                ],
            ],
            'nbLineItems' => 2,
        ];

        $this->assertEquals($expected, $data);
    }

    public function testAutomaticNormalization(): void
    {
        $config = new NormalizerConfiguration();
        $config->registerDefinition(new AutomaticNormalizationDefinition(TestOrder::class));
        $config->registerDefinition(new AutomaticNormalizationDefinition(TestOrderLineItem::class));
        $config->registerDefinition(new AutomaticNormalizationDefinition(TestProductId::class));

        $normalizer = new Normalizer($config);

        $order = new TestOrder();
        $data = $normalizer->normalize($order);

        $expected = [
            'id' => $order->getId(),
            'createdAt' => $order->getCreatedAt(),
            'lineItems' => [
                0 => [
                    'productId' => [
                        'id' => $order->getLineItems()[0]->getProductId(),
                        '__class__' => TestProductId::class,
                    ],
                    'quantity' => $order->getLineItems()[0]->getQuantity(),
                    '__class__' => TestOrderLineItem::class,
                ],
                1 => [
                    'productId' => [
                        'id' => $order->getLineItems()[1]->getProductId(),
                        '__class__' => TestProductId::class,
                    ],
                    'quantity' => $order->getLineItems()[1]->getQuantity(),
                    '__class__' => TestOrderLineItem::class,
                ],
            ],
            '__class__' => TestOrder::class,
        ];

        $this->assertEquals($expected, $data);
    }
}
