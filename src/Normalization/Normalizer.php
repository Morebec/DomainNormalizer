<?php

namespace Morebec\DomSer\Normalization;

use Morebec\DomSer\Normalization\Configuration\NormalizedPropertyDefinition;
use Morebec\DomSer\Normalization\Configuration\NormalizerConfiguration;
use Morebec\DomSer\Normalization\Exception\NormalizationException;
use Morebec\DomSer\ObjectManipulation\ObjectAccessor;

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
     * @param $object
     */
    public function normalize(object $object): array
    {
        $objectClass = \get_class($object);
        $def = $this->configuration->getDefinitionForClass($objectClass);

        if (!$def) {
            throw NormalizationException::ClassDefinitionNotFound($objectClass);
        }

        $accessor = ObjectAccessor::access($object);

        $normalizedForm = [];

        $properties = $def->getProperties();
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
}
