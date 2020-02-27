<?php

namespace Morebec\DomSer\Normalization\Configuration;

/**
 * Configuration for a normalizer.
 */
class NormalizerConfiguration
{
    private $definitions;

    public function __construct()
    {
        $this->definitions = [];
    }

    /**
     * Registers a new definition with this configuration.
     * If a definition already exists for a class, this overwrites it.
     *
     * @return $this
     */
    public function registerDefinition(NormalizationDefinition $definition): self
    {
        $this->definitions[$definition->getClassName()] = $definition;

        return $this;
    }

    /**
     * Indicates if a definition exists for a given class name.
     */
    public function hasDefinitionForClass(string $className): bool
    {
        return \array_key_exists($className, $this->definitions);
    }

    /**
     * Returns the definition associated with a class or null if none found.
     */
    public function getDefinitionForClass(string $className): ?NormalizationDefinition
    {
        if (\array_key_exists($className, $this->definitions)) {
            return $this->definitions[$className];
        }

        // We haven't found a definition, let's see if we can find a compatible definition (parent class)
        $candidates = array_filter($this->definitions, static function (NormalizationDefinition $definition) use ($className) {
            return is_a($className, $definition->getClassName(), true);
        });

        // Do we have candidates ?
        $nbCandidates = \count($candidates);
        if ($nbCandidates === 0) {
            return null;
        }

        // Do we have a single candidate ?
        if ($nbCandidates === 1) {
            return $this->getDefinitionForClass(array_key_first($candidates));
        }

        // We have candidates, let's find the closest match.
        // To do this we will check each candidate and find out the parent distance from this class
        // Given A is parent of B is parent of C: the distance between:
        // C <-> B = 1
        // C <-> A = 2
        // If we have a distance 1 we will use that.
        // Otherwise we will find out the distance for each candidate and return the smallest one

        $classDistances = [];

        foreach ($candidates as $candidate) {
            $distance = 1;

            for ($class = $className, $parent = get_parent_class($class); $parent !== $candidate; $distance++, $class = $parent) {
                if ($distance === 1 && $candidate === $parent) {
                    return $this->getDefinitionForClass($candidate);
                }
            }
            $classDistances[$candidate] = $distance;
        }

        // At this point we should have a single result (PHP only supports single inheritance)
        $match = array_search(min($candidates), $classDistances, true);

        return $this->getDefinitionForClass($match);
    }
}
