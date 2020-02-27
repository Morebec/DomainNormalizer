<?php


namespace Tests\Morebec\DomSer\Normalization\Transformer;

use Morebec\DomSer\Normalization\Transformer\TransformationContext;

/**
 * Simple Helper class to ease the testing of transformers
 */
class TransformationContextMaker
{
    /**
     * Creates a simple Transformation context from a value
     * @param $value
     * @return TransformationContext
     */
    public static function makeContext($value): TransformationContext
    {
        return new TransformationContext('', $value, new \StdClass());
    }
}