<?php

namespace Tests\Morebec\DomainNormalizer\Normalization\Transformer;

use Morebec\DomainNormalizer\ValueTransformer\AsIsValueTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestProductId;

class AsIsValueTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $transformer = new AsIsValueTransformer();

        $this->assertNull($transformer->transform(null));

        $productId = new TestProductId('PRODUCT-id');
        $this->assertEquals($productId, $transformer->transform($productId));
    }
}
