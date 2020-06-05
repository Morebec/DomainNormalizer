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

    /**
     * Maps an object to another class.
     *
     * @param $object
     *
     * @return mixed
     */
    public function map($object, string $destinationClass);

    /**
     * Registers an extraction type transformer.
     */
    public function registerExtractionTypeTransformer(ExtractorTypeTransformerInterface $transformer): void;

    /**
     * Registers ane xtraction tytpe transformer.
     */
    public function registerHydrationTypeTransformer(HydratorTypeTransformerInterface $transformer): void;
}
