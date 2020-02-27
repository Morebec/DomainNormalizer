<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Transformer\ClosurePropertyValueTransformer;
use Morebec\DomSer\Normalization\Transformer\TransformationContext;
use PHPUnit\Framework\TestCase;

class ClosurePropertyValueTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $transformer = new ClosurePropertyValueTransformer(static function (TransformationContext $context) {
            $value = $context->getValue();
            return $value + 1;
        });

        $this->assertEquals(5, $transformer->transform(TransformationContextMaker::makeContext(4)));
    }
}
