<?php

namespace Morebec\DomSer\Normalization\Configuration;

use Morebec\DomSer\Normalization\Transformer\NormalizationPropertyValueTransformer;
use Morebec\DomSer\Normalization\Transformer\PropertyValueTransformerInterface;
use Morebec\DomSer\ValueTransformer\AsIsValueTransformer;

// TODO delete reference to normalizationDefinition
class NormalizedPropertyDefinition
{
    /**
     * @var NormalizationDefinition
     */
    protected $normalizationDefinition;

    /**
     * @var string
     */
    protected $propertyName;

    /**
     * Name to use in the normalized form.
     *
     * @var string
     */
    protected $normalizedName;

    /**
     * Indicates if this property is bound to the class.
     * Set to true to add a property to the normalization that is not part of the class.
     *
     * @var bool
     */
    protected $bound;

    /**
     * @var PropertyValueTransformerInterface
     */
    protected $transformer;

    public function __construct(NormalizationDefinition $normalizationDefinition, string $propertyName)
    {
        $this->normalizationDefinition = $normalizationDefinition;
        $this->propertyName = $propertyName;
        $this->transformer = new NormalizationPropertyValueTransformer(new AsIsValueTransformer());
        $this->normalizedName = $propertyName;
        $this->bound = true;
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
}
