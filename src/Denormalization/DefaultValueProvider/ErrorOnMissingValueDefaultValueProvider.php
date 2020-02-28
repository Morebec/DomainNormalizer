<?php

namespace Morebec\DomainNormalizer\Denormalization\DefaultValueProvider;

use Morebec\DomainNormalizer\Denormalization\DenormalizationContext;
use Morebec\DomainNormalizer\Denormalization\Exception\DenormalizationException;

/**
 * USed to throw errors when a key is missing.
 */
class ErrorOnMissingValueDefaultValueProvider implements DefaultValueProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provideValue(DenormalizationContext $context)
    {
        throw DenormalizationException::MissingKeyInData($context->getKeyName());
    }
}
