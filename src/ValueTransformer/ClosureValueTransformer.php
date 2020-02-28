<?php

namespace Morebec\DomainNormalizer\ValueTransformer;

use Closure;

class ClosureValueTransformer implements ValueTransformerInterface
{
    /**
     * @var Closure
     */
    private $closure;

    /**
     * ClosureTransformation constructor.
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        $closure = $this->closure;

        return $closure($value);
    }
}
