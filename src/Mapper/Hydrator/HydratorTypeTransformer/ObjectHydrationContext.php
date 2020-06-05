<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator\HydratorTypeTransformer;

use Morebec\DomainNormalizer\Mapper\Hydrator\HydrationContext;
use Morebec\DomainNormalizer\Mapper\Hydrator\HydratorInterface;

class ObjectHydrationContext extends HydrationContext
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * ObjectHydrationContext constructor.
     *
     * @param $data
     */
    public function __construct(
        HydratorInterface $hydrator,
        string $className,
        $data,
        string $propertyName,
        HydrationContext $parentContext = null
    ) {
        parent::__construct($hydrator, $className, $data, $parentContext);
        $this->propertyName = $propertyName;
    }

    public static function fromContext(HydrationContext $context, string $propertyName): self
    {
        return new self(
            $context->getHydrator(),
            $context->getClassName(),
            $context->getData(),
            $propertyName,
            $context->getParentContext()
        );
    }
}
