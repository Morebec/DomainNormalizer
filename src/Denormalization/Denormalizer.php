<?php

namespace Morebec\DomainNormalizer\Denormalization;

use Morebec\DomainNormalizer\Denormalization\Configuration\AutomaticDenormalizationDefinition;
use Morebec\DomainNormalizer\Denormalization\Configuration\DenormalizationKeyDefinition;
use Morebec\DomainNormalizer\Denormalization\Configuration\DenormalizerConfiguration;
use Morebec\DomainNormalizer\Denormalization\Configuration\FluentDenormalizationKeyDefinition;
use Morebec\DomainNormalizer\Denormalization\Exception\DenormalizationException;
use Morebec\DomainNormalizer\ObjectManipulation\DoctrineInstantiator;
use Morebec\DomainNormalizer\ObjectManipulation\ObjectAccessor;
use Morebec\DomainNormalizer\ObjectManipulation\ObjectInstantiatorInterface;
use ReflectionClass;

class Denormalizer
{
    /**
     * @var DenormalizerConfiguration
     */
    private $configuration;

    /**
     * @var ObjectInstantiatorInterface
     */
    private $objectInstantiator;

    public function __construct(DenormalizerConfiguration $configuration)
    {
        $this->configuration = $configuration;
        $this->objectInstantiator = new DoctrineInstantiator();
    }

    /**
     * Denormalizes an normalized formed to an object instance.
     *
     * @return mixed
     */
    public function denormalize(array $normalizedForm, string $objectClass)
    {
        $def = $this->configuration->getDefinitionForClass($objectClass);

        if (!$def) {
            throw DenormalizationException::ClassDefinitionNotFound($objectClass);
        }

        $keyDefinitions = $def->getKeyDefinitions();

        if ($def instanceof AutomaticDenormalizationDefinition) {
            $objectClass = $normalizedForm['__class__'];
            $keyDefinitions = array_merge(
                $this->getKeysForAutomaticDefinition($objectClass, $normalizedForm, $def),
                $keyDefinitions
            );

            $keys = [];
            foreach ($keyDefinitions as $key) {
                $keys[$key->getKeyName()] = $key;
            }

            $keyDefinitions = $keys;
        }

        $object = $this->objectInstantiator->instantiate($objectClass);
        $accessor = ObjectAccessor::access($object);

        /** @var DenormalizationKeyDefinition $keyDef */
        foreach ($keyDefinitions as $keyDef) {
            $keyName = $keyDef->getKeyName();
            $denormalizedKeyName = $keyDef->getDenormalizedKeyName();

            // Make sure target class property exists
            if (!$accessor->hasProperty($denormalizedKeyName)) {
                // We Cannot denormalize a key if a corresponding property does not exist
                throw DenormalizationException::ClassKeyNotFound($objectClass, $denormalizedKeyName);
            }

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

            $transformation = $keyDef->getTransformer();
            $value = $transformation->transform($context);

            $accessor->writeProperty($denormalizedKeyName, $value);
        }

        return $object;
    }

    private function getKeysForAutomaticDefinition(
        string $objectClass,
        array $normalizedForm,
        AutomaticDenormalizationDefinition $def
    ): array {
        $r = new ReflectionClass($objectClass);
        $properties = [];
        foreach ($r->getProperties() as $p) {
            $propertyName = $p->getName();
            $propDef = new FluentDenormalizationKeyDefinition($def, $propertyName);
            $properties[] = $propDef;

            $value = $normalizedForm[$propertyName];

            $isObject = \is_array($value) && \array_key_exists('__class__', $value);
            if ($isObject) {
                $propDef->asTransformed($value['__class__']);
            } elseif (\is_array($value) && !empty($value)) {
                $firstIndexKey = array_key_first($value);
                $firstValue = $value[$firstIndexKey];
                $isFirstValueObject = \is_array($firstValue) && \array_key_exists('__class__', $firstValue);
                if ($isFirstValueObject) {
                    $propDef->asArrayOfTransformed($firstValue['__class__']);
                }
            }
        }

        return $properties;
    }
}
