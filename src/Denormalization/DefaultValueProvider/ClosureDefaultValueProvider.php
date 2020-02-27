<?php

namespace Morebec\DomSer\Denormalization\DefaultValueProvider;

use Morebec\DomSer\Denormalization\DenormalizationContext;

class ClosureDefaultValueProvider implements DefaultValueProviderInterface
{
    /**
     * @var \Closure
     */
    private $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    public function provideValue(DenormalizationContext $context)
    {
        $c = $this->closure;

        return $c();
    }
}
