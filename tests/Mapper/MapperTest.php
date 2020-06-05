<?php

namespace Tests\Morebec\DomainNormalizer\Mapper;

use Cassandra\Map;
use Morebec\DomainNormalizer\Mapper\Mapper;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestId;
use Tests\Morebec\DomainNormalizer\TestClasses\TestOrder;
use Tests\Morebec\DomainNormalizer\TestClasses\TestProductId;
use Tests\Morebec\DomainNormalizer\TestClasses\TestValueObject;

class MapperTest extends TestCase
{

    public function testExtractAndHydrate()
    {
        $vo = new TestOrder();
        $mapper = new Mapper();

        $data = $mapper->extract($vo);

        $hydrated = $mapper->hydrate(TestOrder::class, $data);

        $this->assertEquals($vo, $hydrated);
    }

    public function testMap()
    {
        $id = new TestProductId('hello');
        $mapper = new Mapper();

        $mappedId = $mapper->map($id, TestId::class);

        $this->assertEquals((string)$id, (string)$mappedId);
    }
}
