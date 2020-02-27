<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Transformer\StringTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomSer\Normalization\TestProductId;

class StringTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $transformer = new StringTransformer();

        $this->assertEquals(null, $transformer->transform(TransformationContextMaker::makeContext(null)));

        $transformer = new StringTransformer(true);
        $this->assertEquals('', $transformer->transform(TransformationContextMaker::makeContext(null)));
        $this->assertEquals('product-id', $transformer->transform(TransformationContextMaker::makeContext(new TestProductId('product-id'))));
    }
}
