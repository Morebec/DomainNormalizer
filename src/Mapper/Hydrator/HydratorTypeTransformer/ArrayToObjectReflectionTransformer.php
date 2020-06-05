<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\AnnotationReader\AnnotationReader;
use Morebec\DomainNormalizer\Mapper\AnnotationReader\VarType;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydrationContext;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorInterface;
use Morebec\DomainNormalizer\ObjectManipulation\DoctrineInstantiator;
use Morebec\DomainNormalizer\ObjectManipulation\ObjectAccessor;
use PhpDocReader\AnnotationException;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * This is the opposite to the ObjectReflectionArrayTransformer,
 * as in it transforms an array to an object.
 */
class ArrayToObjectReflectionTransformer implements HydratorTypeTransformerInterface
{
    public const TYPE_NAME = 'object';

    private AnnotationReader $annotationReader;

    private DoctrineInstantiator $instantiator;

    public function __construct()
    {
        $this->annotationReader = new AnnotationReader();
        $this->instantiator = new DoctrineInstantiator();
    }

    /**
     * The provbided data must be an array [
     *  'className' the name of the class to instantiate
     *  'data'
     * ].
     *
     * @param $data
     * @param HydratorInterface $hydrator
     *
     * @return mixed|object
     *
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function transform(HydrationContext $context)
    {
        $className = $context->getClassName();
        $hydrator = $context->getHydrator();
        $data = $context->getData();

        $r = new ReflectionClass($className);
        $typedProperties = $this->detectTypes($r);

        $object = $this->instantiator->instantiate($className);
        $accessor = ObjectAccessor::access($object);

        foreach ($data as $key => $value) {
            $currentContext = ObjectHydrationContext::fromContext($context, $key);

            if (!\array_key_exists($key, $typedProperties)) {
                throw new RuntimeException("Could not find property '$key' on class $className. Are you hydrating the right class?");
            }

            $typedProperty = $typedProperties[$key];
            /** @var VarType $type */
            $type = $typedProperty['type'];
            $accessor->writeProperty($key, $hydrator->hydrate($type->getTypeNames()[0], $value, $currentContext));
        }

        return $object;
    }

    public function getTypeName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * Detects the types in use by the properties of the a given class.
     *
     * @throws AnnotationException
     */
    public function detectTypes(ReflectionClass $class): array
    {
        $annotationReader = $this->annotationReader;
        $classProperties = $class->getProperties();

        $props = [];

        foreach ($classProperties as $classProperty) {
            $type = $annotationReader->getPropertyType($classProperty);
            if (!$type) {
                throw new RuntimeException("Could not detect type of {$classProperty->getName()} on {$class->getName()}");
            }
            $detectedType = new VarType($type);
            $classPropertyName = $classProperty->getName();

            $props[$classPropertyName] = [
                'name' => $classPropertyName,
                'type' => $detectedType,
            ];
        }

        return $props;
    }
}
