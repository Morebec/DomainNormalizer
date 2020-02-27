<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\ValueTransformer\LowerCaseStringValueTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomSer\TestClasses\TestProductId;

class LowerCaseStringValueTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $transformer = new LowerCaseStringValueTransformer();

        $this->assertEquals(null, $transformer->transform(null));

        $transformer = new LowerCaseStringValueTransformer(true);
        $this->assertEquals('', $transformer->transform(null));
        $this->assertEquals('product-id', $transformer->transform(new TestProductId('PRODUCT-id')));
    }
}
