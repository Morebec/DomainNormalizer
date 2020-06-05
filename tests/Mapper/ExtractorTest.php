<?php

namespace Tests\Morebec\DomainNormalizer\Mapper;

use Morebec\DomainNormalizer\Mapper\Extractor\ExtractionContext;
use Morebec\DomainNormalizer\Mapper\Extractor\Extractor;
use Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer\CustomExtractorTypeTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Morebec\DomainNormalizer\TestClasses\TestOrder;
use Tests\Morebec\DomainNormalizer\TestClasses\TestValueObject;

class ExtractorTest extends TestCase
{

    public function testMap()
    {
        $extractor = new Extractor();

        $this->assertEquals('hello', $extractor->extract('hello'));
        $this->assertEquals(true, $extractor->extract(true));
        $this->assertEquals(2, $extractor->extract(2));
        $this->assertEquals(4.5, $extractor->extract(4.5));
        $this->assertEquals(null, $extractor->extract(null));

        $this->assertEquals([1,3.2,true, 'hello'], $extractor->extract([1,3.2,true, 'hello']));

        // Object
        $extracted = $extractor->extract(new \RuntimeException('hello'));
        $this->assertEquals([
            'message' => 'hello',
            'code' => 0,
            'file' => __FILE__,
            'line' => 28
        ], $extracted);

        // Custom Type Transformer
        $extractor->registerTransformer(new CustomExtractorTypeTransformer(
            TestValueObject::class,
            static function (ExtractionContext $context) {
                return (string)$context->getData();
            })
        );
        $extracted = $extractor->extract(new TestValueObject('hello-world'));
        $this->assertEquals('hello-world', $extracted);
    }
}
