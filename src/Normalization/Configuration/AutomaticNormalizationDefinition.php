<?php

namespace Morebec\DomainNormalizer\Normalization\Configuration;

class AutomaticNormalizationDefinition extends ObjectNormalizationDefinition
{
    /**
     * @var bool
     */
    private $normalizeClassName;

    public function __construct(string $className, bool $normalizeClassName = true)
    {
        parent::__construct($className);
        $this->normalizeClassName = $normalizeClassName;
    }

    public function normalizeClassName(): bool
    {
        return $this->normalizeClassName;
    }
}
