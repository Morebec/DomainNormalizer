<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\ValueTransformer\AsIsValueTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomSer\TestClasses\TestProductId;

class AsIsValueTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $transformer = new AsIsValueTransformer();

        $this->assertEquals(null, $transformer->transform(null));

        $productId = new TestProductId('PRODUCT-id');
        $this->assertEquals($productId, $transformer->transform($productId));
    }
}
