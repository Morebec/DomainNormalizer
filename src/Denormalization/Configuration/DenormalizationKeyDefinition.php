<?php

namespace Morebec\DomSer\Denormalization\Configuration;

use Morebec\DomSer\Denormalization\DefaultValueProvider\DefaultValueProviderInterface;
use Morebec\DomSer\Denormalization\DefaultValueProvider\ErrorOnMissingValueDefaultValueProvider;
use Morebec\DomSer\Denormalization\Transformer\DenormalizationValueTransformer;
use Morebec\DomSer\Denormalization\Transformer\KeyValueTransformerInterface;
use Morebec\DomSer\ValueTransformer\AsIsValueTransformer;

class DenormalizationKeyDefinition
{
    /** @var string */
    protected $keyName;

    /** @var string */
    protected $denormalizedKeyName;

    /** @var KeyValueTransformerInterface */
    protected $transformer;

    /**
     * @var DefaultValueProviderInterface
     */
    protected $missingValueProvider;

    public function __construct(string $keyName)
    {
        $this->keyName = $keyName;
        $this->denormalizedKeyName = $keyName;
        $this->transformer = new DenormalizationValueTransformer(new AsIsValueTransformer());
        $this->missingValueProvider = new ErrorOnMissingValueDefaultValueProvider();
    }

    public function getKeyName(): string
    {
        return $this->keyName;
    }

    public function getDenormalizedKeyName(): string
    {
        return $this->denormalizedKeyName;
    }

    public function getTransformer(): KeyValueTransformerInterface
    {
        return $this->transformer;
    }

    public function getDefaultValueProvider(): DefaultValueProviderInterface
    {
        return $this->missingValueProvider;
    }
}
