<?php

namespace Morebec\DomainNormalizer\Mapper\AnnotationReader;

use PhpDocReader\AnnotationException;
use PhpDocReader\PhpParser\UseStatementParser;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Reflector;

/**
 * Inspired by https://github.com/PHP-DI/PhpDocReader.
 */
class AnnotationReader
{
    /**
     * @var UseStatementParser
     */
    private $parser;

    private $builtInTypes = [
        'bool',
        'null',
        'boolean',
        'string',
        'int',
        'integer',
        'float',
        'double',
        'array',
        'object',
        'callable',
        'resource',
        'mixed',
        'iterable',
    ];

    /**
     * Enable or disable throwing errors when PhpDoc Errors occur (when parsing annotations).
     *
     * @var bool
     */
    private $ignorePhpDocErrors;

    /**
     * @param bool $ignorePhpDocErrors
     */
    public function __construct($ignorePhpDocErrors = false)
    {
        $this->parser = new UseStatementParser();
        $this->ignorePhpDocErrors = $ignorePhpDocErrors;
    }

    /**
     * Parse the docblock of the property to get the class of the var annotation.
     *
     * @throws AnnotationException
     */
    public function getPropertyType(ReflectionProperty $property): ?string
    {
        // Get the content of the @var annotation
        if (preg_match('/@var\s+([^\s]+)/', $property->getDocComment(), $matches)) {
            list(, $typeString) = $matches;
        } else {
            return null;
        }

        $types = explode('|', $typeString);

        $returnTypes = [];

        foreach ($types as $typeString) {
            $returnTypes[] = $this->resolveType($typeString, $property);
        }

        return implode('|', $returnTypes);
    }

    /**
     * @return string
     *
     * @throws AnnotationException
     */
    private function resolveType(string $type, ReflectionProperty $property)
    {
        if (\in_array($type, $this->builtInTypes)) {
            return $type;
        }

        $appendArray = strpos($type, '[]') !== false;
        $type = str_replace('[]', '', $type);

        $class = $property->getDeclaringClass();

        // If the class name is not fully qualified (i.e. doesn't start with a \)
        if ($type[0] !== '\\') {
            // Try to resolve the FQN using the class context
            $resolvedType = $this->tryResolveFqn($type, $class, $property);

            if (!$resolvedType && !$this->ignorePhpDocErrors) {
                throw new AnnotationException(sprintf('The @var annotation on %s::%s contains a non existent class "%s". '.'Did you maybe forget to add a "use" statement for this annotation?', $class->name, $property->getName(), $type));
            }

            $type = $resolvedType;
        }

        if (!$this->classExists($type) && !$this->ignorePhpDocErrors) {
            throw new AnnotationException(sprintf('The @var annotation on %s::%s contains a non existent class "%s"', $class->name, $property->getName(), $type));
        }

        // Remove the leading \ (FQN shouldn't contain it)
        $type = ltrim($type, '\\');

        if ($appendArray) {
            $type .= '[]';
        }

        return $type;
    }

    /**
     * Attempts to resolve the FQN of the provided $type based on the $class and $member context.
     *
     * @param string $type
     *
     * @return string|null Fully qualified name of the type, or null if it could not be resolved
     */
    private function tryResolveFqn($type, ReflectionClass $class, Reflector $member)
    {
        $alias = ($pos = strpos($type, '\\')) === false ? $type : substr($type, 0, $pos);
        $loweredAlias = strtolower($alias);

        // Retrieve "use" statements
        $uses = $this->parser->parseUseStatements($class);

        if (isset($uses[$loweredAlias])) {
            // Imported classes
            if ($pos !== false) {
                return $uses[$loweredAlias].substr($type, $pos);
            } else {
                return $uses[$loweredAlias];
            }
        } elseif ($this->classExists($class->getNamespaceName().'\\'.$type)) {
            return $class->getNamespaceName().'\\'.$type;
        } elseif (isset($uses['__NAMESPACE__']) && $this->classExists($uses['__NAMESPACE__'].'\\'.$type)) {
            // Class namespace
            return $uses['__NAMESPACE__'].'\\'.$type;
        } elseif ($this->classExists($type)) {
            // No namespace
            return $type;
        }

        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            return null;
        } else {
            // If all fail, try resolving through related traits
            return $this->tryResolveFqnInTraits($type, $class, $member);
        }
    }

    /**
     * Attempts to resolve the FQN of the provided $type based on the $class and $member context, specifically searching
     * through the traits that are used by the provided $class.
     *
     * @param string $type
     *
     * @return string|null Fully qualified name of the type, or null if it could not be resolved
     */
    private function tryResolveFqnInTraits($type, ReflectionClass $class, Reflector $member)
    {
        /** @var ReflectionClass[] $traits */
        $traits = [];

        // Get traits for the class and its parents
        while ($class) {
            $traits = array_merge($traits, $class->getTraits());
            $class = $class->getParentClass();
        }

        foreach ($traits as $trait) {
            // Eliminate traits that don't have the property/method/parameter
            if ($member instanceof ReflectionProperty && !$trait->hasProperty($member->name)) {
                continue;
            } elseif ($member instanceof ReflectionMethod && !$trait->hasMethod($member->name)) {
                continue;
            } elseif ($member instanceof ReflectionParameter && !$trait->hasMethod($member->getDeclaringFunction()->name)) {
                continue;
            }

            // Run the resolver again with the ReflectionClass instance for the trait
            $resolvedType = $this->tryResolveFqn($type, $trait, $member);

            if ($resolvedType) {
                return $resolvedType;
            }
        }

        return null;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    private function classExists($class)
    {
        return class_exists($class) || interface_exists($class);
    }
}
