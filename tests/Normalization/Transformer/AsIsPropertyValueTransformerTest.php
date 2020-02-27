<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Transformer\AsIsPropertyValueTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomSer\Normalization\TestProductId;

class AsIsPropertyValueTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $transformer = new AsIsPropertyValueTransformer();

        $this->assertEquals(null, $transformer->transform(TransformationContextMaker::makeContext(null)));

        $productId = new TestProductId('PRODUCT-id');
        $this->assertEquals($productId, $transformer->transform(TransformationContextMaker::makeContext($productId)));
    }
}
