<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Transformer\StringPropertyValueTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomSer\Normalization\TestProductId;

class StringPropertyValueTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $transformer = new StringPropertyValueTransformer();

        $this->assertEquals(null, $transformer->transform(TransformationContextMaker::makeContext(null)));

        $transformer = new StringPropertyValueTransformer(true);
        $this->assertEquals('', $transformer->transform(TransformationContextMaker::makeContext(null)));
        $this->assertEquals('product-id', $transformer->transform(TransformationContextMaker::makeContext(new TestProductId('product-id'))));
    }
}
