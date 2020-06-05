<?php

namespace Morebec\DomainNormalizer\Mapper;

use Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer\ExtractorTypeTransformerInterface;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer\HydratorTypeTransformerInterface;

interface MapperInterface
{
    /**
     * Extracts data.
     *
     * @param $v
     */
    public function extract($v);

    /**
     * Hydrates data.
     *
     * @param $data
     */
    public function hydrate(string $className, $data);

    public function registerExtractionTypeTransformer(ExtractorTypeTransformerInterface $transformer): void;

    public function registerHydrationTypeTransformer(HydratorTypeTransformerInterface $transformer): void;
}
