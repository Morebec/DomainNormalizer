<?php

namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\ValueTransformer\ClosureValueTransformer;
use PHPUnit\Framework\TestCase;

class ClosureValueTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $transformer = new ClosureValueTransformer(static function ($value) {
            return $value + 1;
        });

        $this->assertEquals(5, $transformer->transform(4));
    }
}
