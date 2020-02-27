<?php


namespace Morebec\DomSer\Normalization\Transformer;


class ClosureTransformer implements FieldTransformerInterface
{
    /**
     * @var \Closure
     */
    private $closure;

    /**
     * ClosureTransformation constructor.
     * @param \Closure $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    public function transform(TransformationContext $context)
    {
        $value = $context->getValue();
        $closure = $this->closure;
        return $closure($value);
    }
}