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
        return ($this->closure)($context);
    }

    public function getTypeName(): string
    {
        return $this->className;
    }
}
