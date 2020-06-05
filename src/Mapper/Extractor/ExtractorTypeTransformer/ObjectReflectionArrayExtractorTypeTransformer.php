<?php

namespace Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Extractor\ExtractionContext;
use Morebec\DomainNormalizer\Mapper\Extractor\ExtractorInterface;
use Morebec\DomainNormalizer\ObjectManipulation\ObjectAccessor;
use ReflectionClass;
use ReflectionException;

/**
 * Transforms an Object into an array using reflection.
 */
class ObjectReflectionArrayExtractorTypeTransformer implements ExtractorTypeTransformerInterface
{
    public const TYPE_NAME = 'object';

    /**
     * {@inheritdoc}
     *
     * @throws ReflectionException
     */
    public function transform(ExtractionContext $context)
    {
        $v = $context->getData();
        $extractor = $context->getExtractor();
        if ($v instanceof \Throwable) {
            return $this->transformWithReflection($v, $extractor);
        }

        return $this->transformWithObjectAccessor($v, $extractor);
    }

    public function getTypeName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param $v
     *
     * @throws ReflectionException
     */
    private function transformWithReflection($v, ExtractorInterface $extractor): array
    {
        $r = new ReflectionClass($v);

        $properties = $r->getProperties();

        $data = [];
        foreach ($properties as $property) {
            $isPublic = $property->isPublic();
            if (!$isPublic) {
                $property->setAccessible(true);
            }
            $value = $property->getValue($v);

            $data[$property->getName()] = $extractor->extract($value);
        }

        return $data;
    }

    private function transformWithObjectAccessor($v, ExtractorInterface $extractor)
    {
        $accessor = ObjectAccessor::access($v);
        $properties = $accessor->getProperties();

        $data = [];
        foreach ($properties as $property) {
            $data[$property] = $extractor->extract($accessor->readProperty($property));
        }

        return $data;
    }
}
