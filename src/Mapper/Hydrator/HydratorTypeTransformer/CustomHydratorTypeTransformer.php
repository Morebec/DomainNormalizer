<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Hydrator\HydrationContext;

class CustomHydratorTypeTransformer implements HydratorTypeTransformerInterface
{
    private string $className;
    private \Closure $closure;

    public function __construct(string $className, \Closure $closure)
    {
        $this->className = $className;
        $this->closure = $closure;
    }

    public function transform(HydrationContext $context)
    {
        return ($this->closure)($context);
    }

    public function getTypeName(): string
    {
        return $this->className;
    }
}
