<?php

namespace Tests\Morebec\DomainNormalizer\Normalization\Transformer;

use Morebec\DomainNormalizer\ValueTransformer\LowerCaseStringValueTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestProductId;

class LowerCaseStringValueTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $transformer = new LowerCaseStringValueTransformer();

        $this->assertNull($transformer->transform(null));

        $transformer = new LowerCaseStringValueTransformer(true);
        $this->assertEquals('', $transformer->transform(null));
        $this->assertEquals('product-id', $transformer->transform(new TestProductId('PRODUCT-id')));
    }
}
