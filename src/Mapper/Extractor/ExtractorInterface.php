<?php

namespace Morebec\DomainNormalizer\Mapper\Extractor;

use Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer\ExtractorTypeTransformerInterface;

/**
 * Extractors are responsible for converting data to scalar data.
 * In the case of objects, it converts them to arrays of scalars.
 */
interface ExtractorInterface
{
    /**
     * Maps a value.
     *
     * @param $v
     *
     * @return mixed
     */
    public function extract($v, ExtractionContext $parentContext = null);

    /**
     * Registers a new transformer.
     *
     * @return mixed
     */
    public function registerTransformer(ExtractorTypeTransformerInterface $transformer);
}
