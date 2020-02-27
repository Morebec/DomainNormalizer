<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Transformer\UppercaseStringPropertyValueTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomSer\Normalization\TestProductId;

class UppercaseStringPropertyValueTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $transformer = new UppercaseStringPropertyValueTransformer();

        $this->assertEquals(null, $transformer->transform(TransformationContextMaker::makeContext(null)));

        $transformer = new UppercaseStringPropertyValueTransformer(true);
        $this->assertEquals('', $transformer->transform(TransformationContextMaker::makeContext(null)));

        $this->assertEquals('PRODUCT-ID', $transformer->transform(TransformationContextMaker::makeContext(new TestProductId('product-id'))));
    }
}
