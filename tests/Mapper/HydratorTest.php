<?php

namespace Tests\Morebec\DomainNormalizer\Mapper;

use Morebec\DomainNormalizer\Mapper\Hydrator\HydrationContext;
use Morebec\DomainNormalizer\Mapper\Hydrator\Hydrator;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer\CustomHydratorTypeTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestValueObject;

class HydratorTest extends TestCase
{

    public function testHydrate()
    {
        $hydrator = new Hydrator();

        $hydrated = $hydrator->hydrate(TestValueObject::class, ['value' => 'hello-world']);


        $hydrator->registerTransformer(new CustomHydratorTypeTransformer(
            TestValueObject::class,
            static function (HydrationContext $context) {
                $method = 'fromString';
                $className = $context->getClassName();
                $data = $context->getData();
                return $className::$method($data);
        }));
        $hydrated = $hydrator->hydrate(TestValueObject::class, 'hello-world');
        $this->assertEquals(new TestValueObject('hello-world'), $hydrated);
    }
}
