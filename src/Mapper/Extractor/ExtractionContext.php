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

    public function __construct(ExtractorInterface $extractor, $data)
    {
        $this->extractor = $extractor;
        $this->data = $data;
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
}
