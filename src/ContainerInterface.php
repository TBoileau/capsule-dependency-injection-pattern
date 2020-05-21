<?php

namespace TBoileau\DependencyInjection;

use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Class ContainerInterface
 * @package TBoileau\DependencyInjection
 */
interface ContainerInterface extends PsrContainerInterface
{
    /**
     * @param string $id
     * @return $this
     */
    public function register(string $id): self;

    /**
     * @param $id
     * @return Definition
     */
    public function getDefinition($id): Definition;

    /**
     * @param $id
     * @param $value
     * @return $this
     */
    public function addParameter($id, $value): self;

    /**
     * @param $id
     * @return mixed
     */
    public function getParameter($id);

    /**
     * @param string $id
     * @param string $class
     * @return $this
     */
    public function addAlias(string $id, string $class): self;
}
