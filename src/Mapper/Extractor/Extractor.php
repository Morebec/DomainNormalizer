<?php

namespace Morebec\DomainNormalizer\Mapper\Extractor;

use Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer\ArrayExtractorTypeTransformer;
use Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer\ExtractorTypeTransformerInterface;
use Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer\NullExtractorTypeTransformer;
use Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer\ObjectReflectionArrayExtractorTypeTransformer;
use Morebec\DomainNormalizer\Mapper\Extractor\ExtractorTypeTransformer\ScalarExtractorTypeTransformer;

class Extractor implements ExtractorInterface
{
    /**
     * @var ExtractorTypeTransformerInterface[]
     */
    private $builtInTransformers;

    /**
     * @var ExtractorTypeTransformerInterface[]
     */
    private $customTransformers;

    public function __construct()
    {
        $this->builtInTransformers = [];
        $this->customTransformers = [];

        $this->registerBuiltInTransformer(new NullExtractorTypeTransformer());
        $this->registerBuiltInTransformer(new ScalarExtractorTypeTransformer());
        $this->registerBuiltInTransformer(new ArrayExtractorTypeTransformer());
        $this->registerBuiltInTransformer(new ObjectReflectionArrayExtractorTypeTransformer());
    }

    /**
     * @param $v
     *
     * @return mixed
     */
    public function extract($v)
    {
        $context = new ExtractionContext($this, $v);

        if ($v === null) {
            return $this->builtInTransformers[NullExtractorTypeTransformer::TYPE_NAME]->transform($context);
        }

        if (is_scalar($v)) {
            return $this->builtInTransformers[ScalarExtractorTypeTransformer::TYPE_NAME]->transform($context);
        }

        if (\is_array($v)) {
            return $this->builtInTransformers[ArrayExtractorTypeTransformer::TYPE_NAME]->transform($context);
        }

        if (\is_object($v)) {
            return $this->extractObject($context);
        }

        throw new \RuntimeException('Could not extract value '.\gettype($v));
    }

    /**
     * {@inheritdoc}
     */
    public function registerTransformer(ExtractorTypeTransformerInterface $transformer)
    {
        $this->customTransformers[$transformer->getTypeName()] = $transformer;
    }

    /**
     * Registers a builtin Transformer.
     */
    private function registerBuiltInTransformer(ExtractorTypeTransformerInterface $transformer)
    {
        $this->builtInTransformers[$transformer->getTypeName()] = $transformer;
    }

    /**
     * @param $v
     *
     * @return mixed
     */
    private function extractObject(ExtractionContext $context)
    {
        $v = $context->getData();
        // Check for other transformers first (some custom transformers can maybe handle it)
        foreach ($this->customTransformers as $customTransformer) {
            if (is_a($v, $customTransformer->getTypeName())) {
                return $customTransformer->transform($context);
            }
        }

        return $this->builtInTransformers[ObjectReflectionArrayExtractorTypeTransformer::TYPE_NAME]->transform($context);
    }
}
