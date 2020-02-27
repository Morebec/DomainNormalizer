<?php

namespace Morebec\DomSer\Denormalization\DefaultValueProvider;

use Morebec\DomSer\Denormalization\DenormalizationContext;

/**
 * Responsible for providing a default value when
 * a key is missing on a normalized form for denormalization purposes.
 */
interface DefaultValueProviderInterface
{
    /**
     * Returns a default value for a given Denormalization context.
     *
     * @return mixed
     */
    public function provideValue(DenormalizationContext $context);
}
