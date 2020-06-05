<?php

namespace Tests\Morebec\DomainNormalizer\Mapper;

use Morebec\DomainNormalizer\Mapper\Mapper;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestOrder;
use Tests\Morebec\DomainNormalizer\TestClasses\TestValueObject;

class MapperTest extends TestCase
{

    public function testExtract()
    {
        $vo = new TestOrder();
        $mapper = new Mapper();

        $data = $mapper->extract($vo);

        $hydrated = $mapper->hydrate(TestOrder::class, $data);

        $this->assertEquals($vo, $hydrated);
    }

    public function testHydrate()
    {

    }
}
