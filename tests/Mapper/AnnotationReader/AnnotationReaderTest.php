<?php

namespace Tests\Morebec\DomainNormalizer\Mapper\AnnotationReader;

use Morebec\DomainNormalizer\Mapper\AnnotationReader\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestOrder;

class AnnotationReaderTest extends TestCase
{

    public function testGetPropertyType(): void
    {
        $reader = new AnnotationReader();

        $r = new \ReflectionClass(new TestOrder());
        $property = $r->getProperty('nullableProperty');
        $type = $reader->getPropertyType($property);

        $this->assertEquals('Tests\Morebec\DomainNormalizer\TestClasses\TestOrderLineItem|null', $type);
    }
}
