<?php

namespace TBoileau\DependencyInjection\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
use TBoileau\DependencyInjection\Container;
use TBoileau\DependencyInjection\NotFoundException;
use TBoileau\DependencyInjection\Tests\Fixtures\Database;
use TBoileau\DependencyInjection\Tests\Fixtures\Foo;
use TBoileau\DependencyInjection\Tests\Fixtures\Router;
use TBoileau\DependencyInjection\Tests\Fixtures\RouterInterface;

/**
 * Class ContainerTest
 * @package TBoileau\DependencyInjection\Tests
 */
class ContainerTest extends TestCase
{
    public function test()
    {
        $container = new Container();

        $container->addAlias(RouterInterface::class, Router::class);

        $container->getDefinition(Foo::class)->setShared(false);

        $container
            ->addParameter("dbUrl", "url")
            ->addParameter("dbName", "name")
            ->addParameter("dbUser", "user")
            ->addParameter("dbPassword", "password")
        ;

        $database1 = $container->get(Database::class);
        $database2 = $container->get(Database::class);
        $this->assertInstanceOf(Database::class, $database1);
        $this->assertInstanceOf(Database::class, $database2);
        $this->assertEquals(spl_object_id($database1), spl_object_id($database2));
        $this->assertInstanceOf(Router::class, $container->get(RouterInterface::class));

        $router1 = $container->get(RouterInterface::class);
        $router2 = $container->get(RouterInterface::class);
        $this->assertEquals(spl_object_id($router1), spl_object_id($router2));

        $foo1 = $container->get(Foo::class);
        $foo2 = $container->get(Foo::class);
        $this->assertNotEquals(spl_object_id($foo1), spl_object_id($foo2));

    }

    public function testIfParameterNotFound()
    {
        $container = new Container();
        $this->expectException(NotFoundExceptionInterface::class);
        $container->getParameter("fail");
    }

    public function testIfClassNotFound()
    {
        $container = new Container();
        $this->expectException(NotFoundExceptionInterface::class);
        $container->get(Bar::class);
    }
}
