<?php

namespace Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer;

use Closure;
use Morebec\DomainNormalizer\Mapper\Extractor\ExtractionContext;

class CustomExtractorTypeTransformer implements ExtractorTypeTransformerInterface
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var Closure
     */
    private $closure;

    public function __construct(string $className, Closure $closure)
    {
        $this->className = $className;
        $this->closure = $closure;
    }

    public function transform(ExtractionContext $context)
    {
        $v = $context->getData();
        $extractor = $context->getExtractor();

        return ($this->closure)($v, $extractor);
    }

    public function getTypeName(): string
    {
        return $this->className;
    }
}
