<?php

namespace Morebec\DomainNormalizer\Denormalization\Configuration;

use Morebec\DomainNormalizer\Denormalization\DefaultValueProvider\DefaultValueProviderInterface;
use Morebec\DomainNormalizer\Denormalization\DefaultValueProvider\ErrorOnMissingValueDefaultValueProvider;
use Morebec\DomainNormalizer\Denormalization\Transformer\DenormalizationValueTransformer;
use Morebec\DomainNormalizer\Denormalization\Transformer\KeyValueTransformerInterface;
use Morebec\DomainNormalizer\ValueTransformer\AsIsValueTransformer;

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
