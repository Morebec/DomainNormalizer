<?php

namespace Morebec\DomSer\Denormalization;

use Morebec\DomSer\Denormalization\Configuration\DenormalizationConfiguration;
use Morebec\DomSer\Denormalization\Configuration\DenormalizationKeyDefinition;
use Morebec\DomSer\Denormalization\Exception\DenormalizationException;
use Morebec\DomSer\ObjectManipulation\DoctrineInstantiator;
use Morebec\DomSer\ObjectManipulation\ObjectAccessor;
use Morebec\DomSer\ObjectManipulation\ObjectInstantiatorInterface;

class Denormalizer
{
    /**
     * @var DenormalizationConfiguration
     */
    private $configuration;

    /**
     * @var ObjectInstantiatorInterface
     */
    private $objectInstantiator;

    public function __construct(DenormalizationConfiguration $configuration)
    {
        $this->configuration = $configuration;
        $this->objectInstantiator = new DoctrineInstantiator();
    }

    /**
     * Denormalizes an normalized formed to an object instance.
     */
    public function denormalize(array $normalizedForm, string $objectClass): object
    {
        $def = $this->configuration->getDefinitionForClass($objectClass);

        if (!$def) {
            throw DenormalizationException::ClassDefinitionNotFound($objectClass);
        }

        $object = $this->objectInstantiator->instantiate($objectClass);
        $accessor = ObjectAccessor::access($object);

        $keyDefinitions = $def->getKeyDefinitions();
        /** @var DenormalizationKeyDefinition $keyDef */
        foreach ($keyDefinitions as $keyDef) {
            $keyName = $keyDef->getKeyName();
            $denormalizedKeyName = $keyDef->getDenormalizedKeyName();

            // Make sure target class property exists
            if (!$accessor->hasProperty($denormalizedKeyName)) {
                // We Cannot denormalize a key if a corresponding property does not exist
                throw DenormalizationException::ClassKeyNotFound($objectClass, $denormalizedKeyName);
            }

            $transformation = $keyDef->getTransformer();
            $defaultValueProvider = $keyDef->getDefaultValueProvider();

            // Ensure the key exists in the data
            $keyExists = \array_key_exists($keyName, $normalizedForm);

            if (!$keyExists) {
                // It does not, execute the missing transformation
                // throw DenormalizationException::MissingKeyInData($keyName);
                $context = new DenormalizationContext($keyName, $denormalizedKeyName, null, $object, $this);
                $value = $keyDef->getDefaultValueProvider()->provideValue($context);
            } else {
                // Get property value
                $value = $normalizedForm[$keyName];
            }
            $context = new DenormalizationContext($keyName, $denormalizedKeyName, $value, $object, $this);
            $value = $transformation->transform($context);

            $accessor->writeProperty($denormalizedKeyName, $value);
        }

        return $object;
    }
}
