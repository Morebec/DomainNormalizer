<?php

namespace Morebec\DomSer\Normalization;

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
     * @var StringPropertyValueTransformer
     */
    private $transformer;

    public function __construct(NormalizationDefinition $normalizationDefinition, string $propertyName)
    {
        $this->normalizationDefinition = $normalizationDefinition;
        $this->propertyName = $propertyName;
        $this->asIs();
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
    public function asIs(): NormalizationDefinition
    {
        $this->transformer = new AsIsPropertyValueTransformer();

        return $this->normalizationDefinition;
    }

    /**
     * Declares this property transformed as a string.
     */
    public function asString(bool $preserveNull = true): NormalizationDefinition
    {
        $this->transformer = new StringPropertyValueTransformer($preserveNull);

        return $this->normalizationDefinition;
    }

    /**
     * Declares this property transformed as given normalised class.
     */
    public function asTransformed(string $className): NormalizationDefinition
    {
        $this->transformer = new NormalizeObjectPropertyValueTransformer($className);

        return $this->normalizationDefinition;
    }

    /**
     * Declares this property transformed as array of given normalised class.
     */
    public function asArrayOfTransformed(string $className): NormalizationDefinition
    {
        $this->transformer = new NormalizeObjectPropertyArrayTransformation($className);

        return $this->normalizationDefinition;
    }

    /**
     * Declares this property transformed as the logic of provided closure.
     */
    public function as(Closure $closure): NormalizationDefinition
    {
        $this->transformedWith(new ClosurePropertyValueTransformer($closure));

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

    private function transformedWith(PropertyValueTransformerInterface $transformer): void
    {
        $this->transformer = $transformer;
    }
}
