<?php

namespace Morebec\DomainNormalizer\Mapper;

use Morebec\DomainNormalizer\Mapper\Extractor\Extractor;
use Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer\ExtractorTypeTransformerInterface;
use Morebec\DomainNormalizer\Mapper\Hydrator\Hydrator;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer\HydratorTypeTransformerInterface;

class Mapper implements MapperInterface
{
    /**
     * @var Extractor
     */
    private $extractor;

    /**
     * @var Hydrator
     */
    private $hydrator;

    public function __construct()
    {
        $this->extractor = new Extractor();
        $this->hydrator = new Hydrator();
    }

    /**
     * {@inheritdoc}
     */
    public function extract($v)
    {
        return $this->extractor->extract($v);
    }

    /**
     * {@inheritdoc}
     */
    public function hydrate(string $className, $data)
    {
        return $this->hydrator->hydrate($className, $data);
    }

    /**
     * Maps an object to another class.
     *
     * @param $object
     *
     * @return mixed|object
     */
    public function map($object, string $destinationClass)
    {
        $data = $this->extract($object);

        return $this->hydrate($destinationClass, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function registerExtractionTypeTransformer(ExtractorTypeTransformerInterface $transformer): void
    {
        $this->extractor->registerTransformer($transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function registerHydrationTypeTransformer(HydratorTypeTransformerInterface $transformer): void
    {
        $this->hydrator->registerTransformer($transformer);
    }
}
