<?php

namespace Morebec\DomSer\Normalization\Transformer;

/**
 * Transforms a value by casting to a string.
 */
class StringPropertyValueTransformer implements PropertyValueTransformerInterface
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
    public function transform(TransformationContext $context)
    {
        $value = $context->getValue();

        if ($value === null) {
            return $this->transformNull ? '' : null;
        }

        return (string) $value;
    }
}