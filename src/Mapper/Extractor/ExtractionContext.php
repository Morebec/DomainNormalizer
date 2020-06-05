<?php

namespace Morebec\DomainNormalizer\Mapper\Extractor;

class ExtractionContext
{
    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /** @var mixed */
    private $data;
    /**
     * @var ExtractionContext
     */
    private $parentContext;

    public function __construct(ExtractorInterface $extractor, $data, ?self $parentContext = null)
    {
        $this->extractor = $extractor;
        $this->data = $data;
        $this->parentContext = $parentContext;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function getExtractor(): ExtractorInterface
    {
        return $this->extractor;
    }

    public function getParentContext(): self
    {
        return $this->parentContext;
    }
}
