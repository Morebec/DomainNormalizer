<?php

namespace Tests\Morebec\DomainNormalizer\ObjectManipulation;

use Morebec\DomainNormalizer\ObjectManipulation\ObjectInspector;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestObject;
use Tests\Morebec\DomainNormalizer\TestClasses\TestOrder;

class ObjectInspectorTest extends TestCase
{

    public function testInspect(): void
    {
        $testObject = new TestOrder();
        $map = ObjectInspector::inspect($testObject);

        var_dump($map);

        $this->assertEquals([
            'createdAt' => ['value' => null, 'type' => null]
        ], $map);
    }
}
