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

    public function __construct(
        HydratorInterface $hydrator,
        string $className,
        $data
    ) {
        $this->hydrator = $hydrator;
        $this->className = $className;
        $this->data = $data;
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
}
