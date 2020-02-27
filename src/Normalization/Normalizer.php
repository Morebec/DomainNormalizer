<?php

namespace Morebec\DomSer\Normalization;

use Morebec\DomSer\Normalization\Configuration\NormalizerConfiguration;
use Morebec\DomSer\Normalization\Exception\NormalizationException;
use Morebec\DomSer\Normalization\Transformer\TransformationContext;
use Morebec\DomSer\ObjectAccessor\ObjectAccessor;

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

        $accessor = new ObjectAccessor($object);

        $normalizedForm = [];

        $properties = $def->getProperties();
        /** @var NormalizedPropertyDefinition $propertyDefinition */
        foreach ($properties as $propertyDefinition) {
            $propertyName = $propertyDefinition->getPropertyName();
            $transformation = $propertyDefinition->getTransformer();

            if (!$accessor->hasProperty($propertyName)) {
                // We Cannot Normalize if a property does not exist
                throw NormalizationException::ClassPropertyNotFound($objectClass, $propertyName);
            }

            // Get property value
            $value = $accessor->readProperty($propertyName);

            $context = new TransformationContext($propertyName, $value, $object, $this);
            $normalizedForm[$propertyName] = $transformation->transform($context);
        }

        return $normalizedForm;
    }
}
