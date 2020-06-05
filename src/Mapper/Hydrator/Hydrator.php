<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator;

use Morebec\DomainNormalizer\Mapper\AnnotationReader\VarType;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer\ArrayHydratorTypeTransformer;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer\ArrayToObjectReflectionTransformer;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer\HydratorTypeTransformerInterface;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer\NullHydratorTypeTransformer;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer\ScalarHydratorTypeTransformer;
use ReflectionException;

class Hydrator implements HydratorInterface
{
    /**
     * @var HydratorTypeTransformerInterface[]
     */
    private $builtInTransformers;

    /**
     * @var HydratorTypeTransformerInterface[]
     */
    private $customTransformers;

    public function __construct()
    {
        $this->builtInTransformers = [];
        $this->customTransformers = [];

        $this->registerBuiltInTransformer(new NullHydratorTypeTransformer());
        $this->registerBuiltInTransformer(new ArrayHydratorTypeTransformer());
        $this->registerBuiltInTransformer(new ScalarHydratorTypeTransformer());
        $this->registerBuiltInTransformer(new ArrayToObjectReflectionTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function registerTransformer(HydratorTypeTransformerInterface $transformer)
    {
        $this->customTransformers[$transformer->getTypeName()] = $transformer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ReflectionException
     * @throws \PhpDocReader\AnnotationException
     */
    public function hydrate(string $className, $data, HydrationContext $parentContext = null)
    {
        $hydrationContext = new HydrationContext($this, $className, $data, $parentContext);
        if ($data === null) {
            return $this->builtInTransformers[NullHydratorTypeTransformer::TYPE_NAME]
                ->transform($hydrationContext);
        }

        if (\in_array($className, ['string', 'bool', 'float', 'int', 'scalar'])) {
            return $this->builtInTransformers[ScalarHydratorTypeTransformer::TYPE_NAME]
                ->transform($hydrationContext);
        }

        $varType = new VarType($className);

        if ($className === 'array' || $varType->isArray()) {
            return $this->builtInTransformers[ArrayHydratorTypeTransformer::TYPE_NAME]
                ->transform($hydrationContext);
        }

        return $this->hydrateObject($hydrationContext);
    }

    /**
     * Registers a builtin Transformer.
     */
    private function registerBuiltInTransformer(HydratorTypeTransformerInterface $transformer)
    {
        $this->builtInTransformers[$transformer->getTypeName()] = $transformer;
    }

    /**
     * Hydrates an Object of a given class with provided data.
     *
     * @return object
     */
    private function hydrateObject(HydrationContext $context)
    {
        // Check for other transformers first (some custom transformers can maybe handle it)
        foreach ($this->customTransformers as $customTransformer) {
            if (is_a($context->getClassName(), $customTransformer->getTypeName(), true)) {
                return $customTransformer->transform($context);
            }
        }

        return $this->builtInTransformers[ArrayToObjectReflectionTransformer::TYPE_NAME]
            ->transform($context);
    }
}
