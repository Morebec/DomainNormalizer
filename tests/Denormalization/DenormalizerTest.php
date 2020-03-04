<?php

namespace Tests\Morebec\DomainNormalizer\Denormalization;

use Morebec\DomainNormalizer\Denormalization\Configuration\DenormalizationConfiguration;
use Morebec\DomainNormalizer\Denormalization\Configuration\ObjectDenormalizationDefinition;
use Morebec\DomainNormalizer\Denormalization\Configuration\ObjectDenormalizationDefinitionFactory;
use Morebec\DomainNormalizer\Denormalization\DenormalizationContext;
use Morebec\DomainNormalizer\Denormalization\Denormalizer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestOrder;
use Tests\Morebec\DomainNormalizer\TestClasses\TestOrderLineItem;
use Tests\Morebec\DomainNormalizer\TestClasses\TestProductId;

class DenormalizerTest extends TestCase
{
    public function testDenormalize(): void
    {
        $config = new DenormalizationConfiguration();

        $config->registerDefinition(ObjectDenormalizationDefinitionFactory::forClass(
            TestOrder::class,
            static function (ObjectDenormalizationDefinition $d) {
                $d->key('ID')->renamedTo('id');
                $d->key('createdAt')->as(static function (DenormalizationContext $context) {
                    return strtotime($context->getValue());
                });
                $d->key('lineItems')->asArrayOfTransformed(TestOrderLineItem::class);
            })
        );

        $config->registerDefinition(ObjectDenormalizationDefinitionFactory::forClass(
           TestOrderLineItem::class,
           static function (ObjectDenormalizationDefinition $d) {
               $d->key('quantity');
               $d->key('productId')->as(static function (DenormalizationContext $context) {
                   return new TestProductId($context->getValue());
               });
           }
        ));

        $denormalizer = new Denormalizer($config);

        $data = [
            'ID' => uniqid('', true),
            'createdAt' => (new \DateTime())->format('Y-m-d'),
            'lineItems' => [
                [
                    'quantity' => 5,
                    'productId' => uniqid('', true),
                ],
                [
                    'quantity' => 2,
                    'productId' => uniqid('', true),
                ],
            ],
            'nbLineItems' => 2,
        ];

        /** @var TestOrder $object */
        $object = $denormalizer->denormalize($data, TestOrder::class);

        $this->assertEquals($data['ID'], $object->getId());
        $this->assertEquals(strtotime($data['createdAt']), $object->getCreatedAt());
        $this->assertCount(2, $object->getLineItems());
        $this->assertEquals($data['lineItems'][0]['productId'], $object->getLineItems()[0]->getProductId());
        $this->assertEquals($data['lineItems'][0]['quantity'], $object->getLineItems()[0]->getQuantity());

        $this->assertEquals($data['lineItems'][1]['productId'], $object->getLineItems()[1]->getProductId());
        $this->assertEquals($data['lineItems'][1]['quantity'], $object->getLineItems()[1]->getQuantity());
    }
}
