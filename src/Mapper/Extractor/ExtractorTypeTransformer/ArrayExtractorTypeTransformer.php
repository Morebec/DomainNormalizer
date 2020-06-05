<?php

namespace Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Extractor\ExtractionContext;

class ArrayExtractorTypeTransformer implements ExtractorTypeTransformerInterface
{
    public const TYPE_NAME = 'array';

    public function transform(ExtractionContext $context)
    {
        $v = $context->getData();
        $extractor = $context->getExtractor();

        return array_map(static function ($v) use ($extractor) {
            return $extractor->extract($v);
        }, $v);
    }

    public function getTypeName(): string
    {
        return self::TYPE_NAME;
    }
}
