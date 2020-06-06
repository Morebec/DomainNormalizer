<?php

namespace Morebec\DomainNormalizer\Normalization;

use Morebec\DomainNormalizer\Normalization\Configuration\AutomaticNormalizationDefinition;
use Morebec\DomainNormalizer\Normalization\Configuration\FluentNormalizedPropertyDefinition;
use Morebec\DomainNormalizer\Normalization\Configuration\NormalizedPropertyDefinition;
use Morebec\DomainNormalizer\Normalization\Configuration\NormalizerConfiguration;
use Morebec\DomainNormalizer\Normalization\Exception\NormalizationException;
use Morebec\DomainNormalizer\ObjectManipulation\ObjectAccessor;
use Morebec\DomainNormalizer\ObjectManipulation\ObjectAccessorInterface;
use ReflectionClass;
use ReflectionException;

class Normalizer
{
    /**
     * @var NormalizerConfiguration
     */
    private $configuration;

    public function __construct(NormalizerConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Normalizes an object.
     *
     * @param mixed
     *
     * @throws ReflectionException
     */
    public function normalize($object): array
    {
        $objectClass = \get_class($object);
        $def = $this->configuration->getDefinitionForClass($objectClass);

        if (!$def) {
            throw NormalizationException::ClassDefinitionNotFound($objectClass);
        }

        $accessor = ObjectAccessor::access($object);

        $normalizedForm = [];

        $properties = $def->getProperties();
        if ($def instanceof AutomaticNormalizationDefinition) {
            $properties = array_merge(
                $this->getPropertiesForAutomaticDefinition($object, $def, $accessor),
                $properties
            );
            $props = [];
            foreach ($properties as $p) {
                $props[$p->getPropertyName()] = $p;
            }
            $properties = $props;
        }

        /** @var NormalizedPropertyDefinition $propertyDefinition */
        foreach ($properties as $propertyDefinition) {
            $propertyName = $propertyDefinition->getPropertyName();
            $transformation = $propertyDefinition->getTransformer();

            if (!$accessor->hasProperty($propertyName) && $propertyDefinition->isBound()) {
                // We Cannot Normalize if a property does not exist
                throw NormalizationException::ClassPropertyNotFound($objectClass, $propertyName);
            }

            // Get property value
            $value = $propertyDefinition->isBound() ? $accessor->readProperty($propertyName) : null;

            $context = new NormalizationContext($propertyName, $value, $object, $this);
            $normalizedForm[$propertyDefinition->getNormalizedName()] = $transformation->transform($context);
        }

        return $normalizedForm;
    }

    /**
     * @param object $object
     *
     * @throws ReflectionException
     */
    private function getPropertiesForAutomaticDefinition(
        $object,
        AutomaticNormalizationDefinition $def,
        ObjectAccessorInterface $accessor
    ): array {
        $r = new ReflectionClass($object);
        $properties = [];
        foreach ($r->getProperties() as $p) {
            $propertyName = $p->getName();
            $propDef = new FluentNormalizedPropertyDefinition($def, $propertyName);

            $value = $accessor->readProperty($propertyName);

            if (\is_object($value)) {
                $propDef->asTransformed(\get_class($value));
            } elseif (\is_array($value) && !empty($value)) {
                $firstIndexKey = array_key_first($value);
                $firstValue = $value[$firstIndexKey];
                if (\is_object($firstValue)) {
                    $propDef->asArrayOfTransformed(\get_class($firstValue));
                }
            }

            $properties[] = $propDef;
        }

        if ($def->normalizeClassName()) {
            // Add an unbound property for the class name
            $classProp = new FluentNormalizedPropertyDefinition($def, '__class__');
            $classProp->unbound()->as(static function (NormalizationContext $context) use ($r) {
                return $r->getName();
            });
            $properties[] = $classProp;
        }

        return $properties;
    }
}
