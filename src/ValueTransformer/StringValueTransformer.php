<?php

namespace Morebec\DomainNormalizer\ValueTransformer;

/**
 * Transforms a value by casting to a string.
 */
class StringValueTransformer implements ValueTransformerInterface
{
    /**
     * @var bool indicates if null values should be preserved as is or transformed to ""
     */
    private $transformNull;

    public function __construct(bool $transformNull = false)
    {
        $this->transformNull = $transformNull;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($value === null) {
            return $this->transformNull ? '' : null;
        }

        return (string) $value;
    }
}
