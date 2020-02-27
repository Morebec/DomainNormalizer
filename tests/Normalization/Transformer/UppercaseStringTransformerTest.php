<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Transformer\UppercaseStringTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomSer\Normalization\TestProductId;

class UppercaseStringTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $transformer = new UppercaseStringTransformer();

        $this->assertEquals(null, $transformer->transform(TransformationContextMaker::makeContext(null)));

        $transformer = new UppercaseStringTransformer(true);
        $this->assertEquals('', $transformer->transform(TransformationContextMaker::makeContext(null)));

        $this->assertEquals('PRODUCT-ID', $transformer->transform(TransformationContextMaker::makeContext(new TestProductId('product-id'))));
    }
}
