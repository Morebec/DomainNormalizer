<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Transformer\ClosureTransformer;
use PHPUnit\Framework\TestCase;

class ClosureTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $transformer = new ClosureTransformer(static function ($value) {
            return $value + 1;
        });

        $this->assertEquals(5, $transformer->transform(TransformationContextMaker::makeContext(4)));
    }
}
