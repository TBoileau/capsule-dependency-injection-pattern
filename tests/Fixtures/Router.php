<?php

namespace TBoileau\DependencyInjection\Tests\Fixtures;

/**
 * Class Router
 * @package TBoileau\DependencyInjection\Tests\Fixtures
 */
class Router implements RouterInterface
{
    public function __construct(Foo $foo)
    {
    }

    public function call()
    {
        // TODO: Implement call() method.
    }
}
