<?php

namespace Morebec\DomSer\Normalization\Configuration;

use Closure;
use Morebec\DomSer\Normalization\Transformer\AsIsPropertyValueTransformer;
use Morebec\DomSer\Normalization\Transformer\ClosurePropertyValueTransformer;
use Morebec\DomSer\Normalization\Transformer\NormalizeObjectPropertyArrayTransformation;
use Morebec\DomSer\Normalization\Transformer\NormalizeObjectPropertyValueTransformer;
use Morebec\DomSer\Normalization\Transformer\PropertyValueTransformerInterface;
use Morebec\DomSer\Normalization\Transformer\StringPropertyValueTransformer;

class NormalizedPropertyDefinition
{
    /**
     * @var NormalizationDefinition
     */
    private $normalizationDefinition;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * Name to use in the normalized form.
     *
     * @var string
     */
    private $normalizedName;

    /**
     * Indicates if this property is bound to the class.
     * Set to true to add a property to the normalization that is not part of the class.
     *
     * @var bool
     */
    private $bound;

    /**
     * @var StringPropertyValueTransformer
     */
    private $transformer;

    public function __construct(NormalizationDefinition $normalizationDefinition, string $propertyName)
    {
        $this->normalizationDefinition = $normalizationDefinition;
        $this->propertyName = $propertyName;
        $this->asIs();
        $this->normalizedName = $propertyName;
        $this->bound = true;
    }

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
     *
     * @return NormalizedPropertyDefinition
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
        $this->transformer = new AsIsPropertyValueTransformer();

        return $this;
    }

    /**
     * Declares this property transformed as a string.
     */
    public function asString(bool $preserveNull = true): self
    {
        $this->transformer = new StringPropertyValueTransformer($preserveNull);

        return $this;
    }

    /**
     * Declares this property transformed as given normalised class.
     */
    public function asTransformed(string $className): self
    {
        $this->transformer = new NormalizeObjectPropertyValueTransformer($className);

        return $this;
    }

    /**
     * Declares this property transformed as array of given normalised class.
     */
    public function asArrayOfTransformed(string $className): self
    {
        $this->transformer = new NormalizeObjectPropertyArrayTransformation($className);

        return $this;
    }

    /**
     * Declares this property transformed as the logic of provided closure.
     */
    public function as(Closure $closure): self
    {
        $this->transformedWith(new ClosurePropertyValueTransformer($closure));

        return $this;
    }

    /**
     * Ends this property definition.
     */
    public function end(): NormalizationDefinition
    {
        return $this->normalizationDefinition;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function getTransformer(): PropertyValueTransformerInterface
    {
        return $this->transformer;
    }

    public function getNormalizedName(): string
    {
        return $this->normalizedName;
    }

    public function isBound(): bool
    {
        return $this->bound;
    }

    private function transformedWith(PropertyValueTransformerInterface $transformer): void
    {
        $this->transformer = $transformer;
    }
}
