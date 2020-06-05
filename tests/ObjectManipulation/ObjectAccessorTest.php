<?php

namespace Tests\Morebec\DomainNormalizer\ObjectManipulation;

use Morebec\DomainNormalizer\ObjectManipulation\ObjectAccessor;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestObject;

class ObjectAccessorTest extends TestCase
{

    public function testReadProperty()
    {
        $t = new TestObject('test', 'desc');
        $oa = ObjectAccessor::access($t);

        $this->assertEquals('test', $oa->readProperty('name'));
        $this->assertEquals('test', $oa->name);
    }

    public function testWriteProperty()
    {
        $t = new TestObject('test', 'desc');
        $oa = ObjectAccessor::access($t);

        $oa->writeProperty('name', 'Hello');
        $oa->description = 'World';

        $this->assertEquals('Hello', $oa->readProperty('name'));
        $this->assertEquals('World', $oa->readProperty('description'));
    }

    public function testHasProperty()
    {
        $t = new TestObject('test', 'desc');
        $oa = ObjectAccessor::access($t);
        $this->assertTrue($oa->hasProperty('name'));
        $this->assertFalse($oa->hasProperty('notFound'));
    }

    public function testGetProperties()
    {
        $t = new TestObject('test', 'desc');
        $oa = ObjectAccessor::access($t);

        $props = $oa->getProperties();

        $this->assertEquals(['name', 'description'], $props);
    }
}

