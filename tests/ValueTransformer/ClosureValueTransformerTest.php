<?php

namespace Tests\Morebec\DomainNormalizer\Normalization\Transformer;

use Morebec\DomainNormalizer\ValueTransformer\ClosureValueTransformer;
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
