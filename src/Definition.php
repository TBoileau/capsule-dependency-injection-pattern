<?php

namespace TBoileau\DependencyInjection;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Class Definition
 * @package TBoileau\DependencyInjection
 */
class Definition
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var bool
     */
    private bool $shared;

    /**
     * @var array
     */
    private array $aliases;

    /**
     * @var ReflectionMethod|null
     */
    private ?ReflectionMethod $factory;

    /**
     * @var Definition[]
     */
    private array $dependencies;

    /**
     * @var ReflectionClass
     */
    private ReflectionClass $class;

    /**
     * Definition constructor.
     * @param string $id
     * @param bool $shared
     * @param array $aliases
     * @param array $dependencies
     * @param null|ReflectionMethod $factory
     * @throws \ReflectionException
     */
    public function __construct(
        string $id,
        bool $shared = true,
        array $aliases = [],
        array $dependencies = [],
        ?ReflectionMethod $factory = null
    ) {
        $this->id = $id;
        $this->shared = $shared;
        $this->aliases = $aliases;
        $this->dependencies = $dependencies;
        $this->factory = $factory;
        $this->class = new ReflectionClass($id);
    }

    /**
     * @param bool $shared
     * @return self
     */
    public function setShared(bool $shared): self
    {
        $this->shared = $shared;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShared(): bool
    {
        return $this->shared;
    }

    /**
     * @param ContainerInterface $container
     * @return object
     */
    public function newInstance(ContainerInterface $container): object
    {
        if ($this->factory !== null) {
            $this->factory->invokeArgs(null, $this->resolve($container, $this->factory));
        }

        $constructor = $this->class->getConstructor();

        if (null === $constructor) {
            return $this->class->newInstance();
        }

        return $this->class->newInstanceArgs($this->resolve($container, $constructor));
    }

    /**
     * @param ContainerInterface $container
     * @param ReflectionMethod $method
     * @return array
     */
    private function resolve(ContainerInterface $container, ReflectionMethod $method): array
    {
        return array_map(function (ReflectionParameter $param) use ($container) {
            if ($param->getClass() === null) {
                return $container->getParameter($param->getName());
            }

            return $container->get($param->getClass()->getName());
        }, $method->getParameters());
    }
}
