<?php

namespace Morebec\DomSer\Denormalization\Configuration;

use Morebec\DomSer\Denormalization\DefaultValueProvider\ClosureDefaultValueProvider;
use Morebec\DomSer\Denormalization\DenormalizationContext;
use Morebec\DomSer\Denormalization\Transformer\DenormalizationClosureValueTransformer;
use Morebec\DomSer\Denormalization\Transformer\DenormalizeKeyAsObjectArrayTransformer;
use Morebec\DomSer\Denormalization\Transformer\DenormalizeKeyAsObjectTransformer;

class FluentDenormalizationKeyDefinition extends DenormalizationKeyDefinition
{
    /**
     * @var FluentDenormalizationDefinition
     */
    private $denormalizationDefinition;

    /**
     * FluentDenormalizedKeyDefinition constructor.
     */
    public function __construct(DenormalizationDefinition $denormalizationDefinition, string $keyName)
    {
        parent::__construct($keyName);
        $this->denormalizationDefinition = $denormalizationDefinition;
    }

    public function default(\Closure $closure): self
    {
        $this->missingValueProvider = new ClosureDefaultValueProvider($closure);

        return $this;
    }

    public function renamedTo(string $name): self
    {
        $this->denormalizedKeyName = $name;

        return $this;
    }

    public function defaultValue($value): self
    {
        $this->default(static function (DenormalizationContext $context) use (&$value) {
            return $value;
        });

        return $this;
    }

    /**
     * Declares this key as to be transformed to a given class instance.
     *
     * @return $this
     */
    public function asTransformed(string $className): self
    {
        $this->transformer = new DenormalizeKeyAsObjectTransformer($className);

        return $this;
    }

    /**
     * Declares this key as being an array whose values should be transformed to a given class instance.
     *
     * @return $this
     */
    public function asArrayOfTransformed(string $className): self
    {
        $this->transformer = new DenormalizeKeyAsObjectArrayTransformer($className);

        return $this;
    }

    public function as(\Closure $closure): self
    {
        $this->transformer = new DenormalizationClosureValueTransformer($closure);

        return $this;
    }
}
