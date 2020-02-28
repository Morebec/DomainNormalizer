<?php

namespace Morebec\DomainNormalizer\ValueTransformer;

interface ValueTransformerInterface
{
    /**
     * Transforms a value according to this transformer's contract.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform($value);
}
