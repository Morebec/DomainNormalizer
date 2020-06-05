<?php

namespace Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Extractor\ExtractionContext;

class NullExtractorTypeTransformer implements ExtractorTypeTransformerInterface
{
    public const TYPE_NAME = 'null';

    public function transform(ExtractionContext $context)
    {
        return null;
    }

    public function getTypeName(): string
    {
        return self::TYPE_NAME;
    }
}
