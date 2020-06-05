<?php

namespace Morebec\DomainNormalizer\Mapper\Hydrator;

class HydrationContext
{
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /** @var string */
    private $className;

    /** @var mixed */
    private $data;
    /**
     * @var HydrationContext|null
     */
    private $parentContext;

    public function __construct(
        HydratorInterface $hydrator,
        string $className,
        $data,
        self $parentContext = null
    ) {
        $this->hydrator = $hydrator;
        $this->className = $className;
        $this->data = $data;
        $this->parentContext = $parentContext;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
    }

    public function getParentContext(): ?self
    {
        return $this->parentContext;
    }
}
