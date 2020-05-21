<?php

namespace TBoileau\DependencyInjection;

use ReflectionClass;
use ReflectionParameter;

/**
 * Class Container
 * @package TBoileau\DependencyInjection
 */
class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private array $parameters = [];

    /**
     * @var Definition[]
     */
    private array $definitions = [];

    /**
     * @var array
     */
    private array $instances = [];

    /**
     * @var array
     */
    private array $aliases = [];

    /**
     * @param $id
     * @return $this
     */
    public function register(string $id): self
    {
        $reflectionClass = new ReflectionClass($id);

        if ($reflectionClass->isInterface()) {
            $this->register($this->aliases[$id]);
            $this->definitions[$id] = &$this->definitions[$this->aliases[$id]];
            return $this;
        }

        $dependencies = [];

        if (null !== $reflectionClass->getConstructor()) {
            $dependencies = array_map(
                fn (ReflectionParameter $parameter) => $this->getDefinition($parameter->getClass()->getName()),
                array_filter(
                    $reflectionClass->getConstructor()->getParameters(),
                    fn (ReflectionParameter $parameter) => $parameter->getClass()
                )
            );
        }

        $aliases = array_filter($this->aliases, fn (string $alias) => $id === $alias);

        $this->definitions[$id] = new Definition($id, true, $aliases, $dependencies);

        return $this;
    }

    /**
     * @param $id
     * @return Definition
     */
    public function getDefinition($id): Definition
    {
        if (!isset($this->definitions[$id])) {
            $this->register($id);
        }

        return $this->definitions[$id];
    }

    /**
     * @param $id
     * @param $value
     * @return $this
     */
    public function addParameter($id, $value): self
    {
        $this->parameters[$id] = $value;

        return $this;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getParameter($id)
    {
        if (!isset($this->parameters[$id])) {
            throw new NotFoundException();
        }
        return $this->parameters[$id];
    }

    /**
     * @param string $id
     * @return mixed|object
     * @throws NotFoundException
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            if (!class_exists($id) && !interface_exists($id)) {
                throw new NotFoundException();
            }

            $instance = $this->getDefinition($id)->newInstance($this);

            if (!$this->getDefinition($id)->isShared()) {
                return $instance;
            }

            $this->instances[$id] = $instance;
        }

        return $this->instances[$id];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->instances[$id]);
    }

    /**
     * @param string $id
     * @param string $class
     * @return $this
     */
    public function addAlias(string $id, string $class): self
    {
        $this->aliases[$id] = $class;

        return $this;
    }
}
