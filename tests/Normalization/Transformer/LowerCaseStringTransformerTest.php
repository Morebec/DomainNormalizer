<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Transformer\LowerCaseStringTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomSer\Normalization\TestProductId;

class LowerCaseStringTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $transformer = new LowerCaseStringTransformer();

        $this->assertEquals(null, $transformer->transform(TransformationContextMaker::makeContext(null)));

        $transformer = new LowerCaseStringTransformer(true);
        $this->assertEquals('', $transformer->transform(TransformationContextMaker::makeContext(null)));
        $this->assertEquals('product-id', $transformer->transform(TransformationContextMaker::makeContext(new TestProductId('PRODUCT-id'))));
    }
}
