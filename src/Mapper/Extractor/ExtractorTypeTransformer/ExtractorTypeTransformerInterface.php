<?php

namespace Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Extractor\ExtractionContext;

/**
 * Type transformers are responsible for transforming data based on their type.
 * Based on the type of the value provided it will apply a transformation to a scalar value.
 */
interface ExtractorTypeTransformerInterface
{
    /**
     * Transforms a value v.
     *
     * @return mixed
     */
    public function transform(ExtractionContext $context);

    /**
     * Returns the name of the type that is transformable.
     */
    public function getTypeName(): string;
}
