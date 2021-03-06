<?php

namespace Morebec\DomainNormalizer\Normalization\Configuration;

use Closure;
use Morebec\DomainNormalizer\Normalization\Transformer\NormalizationClosurePropertyValueTransformer;
use Morebec\DomainNormalizer\Normalization\Transformer\NormalizationPropertyValueTransformer;
use Morebec\DomainNormalizer\Normalization\Transformer\NormalizeObjectArrayPropertyValueTransformer;
use Morebec\DomainNormalizer\Normalization\Transformer\NormalizeObjectPropertyValueTransformer;
use Morebec\DomainNormalizer\Normalization\Transformer\PropertyValueTransformerInterface;
use Morebec\DomainNormalizer\ValueTransformer\AsIsValueTransformer;
use Morebec\DomainNormalizer\ValueTransformer\StringValueTransformer;

class FluentNormalizedPropertyDefinition extends NormalizedPropertyDefinition
{
    /**
     * Declares this property as not bound to the class.
     *
     * @return $this
     */
    public function unbound(): self
    {
        $this->bound = false;

        return $this;
    }

    public function renamedTo(string $normalizedName): self
    {
        $this->normalizedName = $normalizedName;

        return $this;
    }

    /**
     * Shortcut for defining the next property definition.
     */
    public function property(string $property): self
    {
        return $this->normalizationDefinition->property($property);
    }

    /**
     * Explicitly declares this property definition with an as is transformation.
     */
    public function asIs(): self
    {
        $this->transformedWith(new NormalizationPropertyValueTransformer(new AsIsValueTransformer()));

        return $this;
    }

    /**
     * Declares this property transformed as a string.
     */
    public function asString(bool $preserveNull = true): self
    {
        $this->transformer = new NormalizationPropertyValueTransformer(new StringValueTransformer($preserveNull));

        return $this;
    }

    /**
     * Declares this property transformed as given normalised class.
     */
    public function asTransformed(string $className): self
    {
        $this->transformedWith(new NormalizeObjectPropertyValueTransformer($className));

        return $this;
    }

    /**
     * Declares this property transformed as array of given normalised class.
     */
    public function asArrayOfTransformed(string $className): self
    {
        $this->transformedWith(new NormalizeObjectArrayPropertyValueTransformer($className));

        return $this;
    }

    /**
     * Declares this property transformed as the logic of provided closure.
     */
    public function as(Closure $closure): self
    {
        $this->transformedWith(new NormalizationClosurePropertyValueTransformer($closure));

        return $this;
    }

    /**
     * Sets the transformer to use for this property.
     */
    public function transformedWith(PropertyValueTransformerInterface $transformer): void
    {
        $this->transformer = $transformer;
    }

    /**
     * Ends this property definition.
     */
    public function end(): NormalizationDefinition
    {
        return $this->normalizationDefinition;
    }
}
