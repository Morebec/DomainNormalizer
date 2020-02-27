<?php

namespace Morebec\DomSer\Denormalization\DefaultValueProvider;

use Morebec\DomSer\Denormalization\DenormalizationContext;
use Morebec\DomSer\Denormalization\Exception\DenormalizationException;

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
