<?php

namespace Tests\Morebec\DomainNormalizer\Normalization\Transformer;

use Morebec\DomainNormalizer\ValueTransformer\UppercaseStringValueTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestProductId;

class UppercaseStringValueTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $transformer = new UppercaseStringValueTransformer();

        $this->assertNull($transformer->transform(null));

        $transformer = new UppercaseStringValueTransformer(true);
        $this->assertEquals('', $transformer->transform(null));

        $this->assertEquals('PRODUCT-ID', $transformer->transform(new TestProductId('product-id')));
    }
}
