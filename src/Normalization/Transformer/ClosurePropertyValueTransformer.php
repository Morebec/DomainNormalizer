<?php

namespace Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Exception\NormalizationException;

class ClosurePropertyValueTransformer implements PropertyValueTransformerInterface
{
    /**
     * @var \Closure
     */
    private $closure;

    /**
     * ClosureTransformation constructor.
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(TransformationContext $context)
    {
        $value = $context->getValue();
        $closure = $this->closure;

        try {
            return $closure($context);
        } catch (\Exception $e) {
            throw new NormalizationException("Could not normalize property '{$context->getPropertyName()}' of class {$context->getObjectClassName()}: {$e->getMessage()}", $e->getCode(), $e->getPrevious());
        }
    }
}
