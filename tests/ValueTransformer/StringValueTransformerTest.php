<?php

namespace Tests\Morebec\DomainNormalizer\Normalization\Transformer;

use Morebec\DomainNormalizer\ValueTransformer\StringValueTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestProductId;

class StringValueTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $transformer = new StringValueTransformer();

        $this->assertEquals(null, $transformer->transform(null));

        $transformer = new StringValueTransformer(true);
        $this->assertEquals('', $transformer->transform(null));
        $this->assertEquals('product-id', $transformer->transform(new TestProductId('product-id')));
    }
}
