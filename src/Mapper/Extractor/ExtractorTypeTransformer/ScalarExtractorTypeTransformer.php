<?php

namespace Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Extractor\ExtractionContext;

class ScalarExtractorTypeTransformer implements ExtractorTypeTransformerInterface
{
    public const TYPE_NAME = 'scalar';

    public function transform(ExtractionContext $context)
    {
        $v = $context->getData();
        if (!is_scalar($v)) {
            throw new \InvalidArgumentException('Provided value is not a scalar');
        }

        return $v;
    }

    public function getTypeName(): string
    {
        return self::TYPE_NAME;
    }
}
