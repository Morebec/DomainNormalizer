<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\ValueTransformer\UppercaseStringValueTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomSer\TestClasses\TestProductId;

class UppercaseStringValueTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $transformer = new UppercaseStringValueTransformer();

        $this->assertEquals(null, $transformer->transform(null));

        $transformer = new UppercaseStringValueTransformer(true);
        $this->assertEquals('', $transformer->transform(null));

        $this->assertEquals('PRODUCT-ID', $transformer->transform(new TestProductId('product-id')));
    }
}
