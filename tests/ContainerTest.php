<?php

namespace TBoileau\DependencyInjection\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
use TBoileau\DependencyInjection\Container;
use TBoileau\DependencyInjection\Tests\Fixtures\Bar;
use TBoileau\DependencyInjection\Tests\Fixtures\BarFactory;
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
    public function test if shared service is found()
    {
        $container = new Container();
        $foo1 = $container->get(Foo::class);
        $this->assertInstanceOf(Foo::class, $foo1);
        $foo2 = $container->get(Foo::class);
        $this->assertEquals(spl_object_id($foo1), spl_object_id($foo2));
    }

    public function test if non shared service is found()
    {
        $container = new Container();
        $container->getDefinition(Foo::class)->setShared(false);
        $foo1 = $container->get(Foo::class);
        $this->assertInstanceOf(Foo::class, $foo1);
        $foo2 = $container->get(Foo::class);
        $this->assertNotEquals(spl_object_id($foo1), spl_object_id($foo2));
    }

    public function test if service with injection is found()
    {
        $container = new Container();
        $bar = $container->get(Bar::class);
        $this->assertInstanceOf(Bar::class, $bar);
        $this->assertInstanceOf(Foo::class, $bar->foo);
    }

    public function test if service with parameters is found()
    {
        $container = new Container();
        $container
            ->addParameter("dbUrl", "url")
            ->addParameter("dbName", "name")
            ->addParameter("dbUser", "user")
            ->addParameter("dbPassword", "password")
        ;
        $database = $container->get(Database::class);
        $this->assertInstanceOf(Database::class, $database);
    }

    public function test if service alias is found()
    {
        $container = new Container();
        $container->addAlias(RouterInterface::class, Router::class);
        $router = $container->get(RouterInterface::class);
        $this->assertInstanceOf(RouterInterface::class, $router);
        $this->assertInstanceOf(Router::class, $router);
    }

    public function test if service is found with factory()
    {
        $container = new Container();
        $container->addFactory(Bar::class, BarFactory::class, "create");
        $bar = $container->get(Bar::class);
        $this->assertInstanceOf(Bar::class, $bar);
        $this->assertInstanceOf(Foo::class, $bar->foo);
    }

    public function test if parameter is found()
    {
        $container = new Container();
        $container->addParameter("key", "value");
        $this->assertEquals("value", $container->getParameter("key"));
    }

    public function test if parameter not found()
    {
        $container = new Container();
        $this->expectException(NotFoundExceptionInterface::class);
        $container->getParameter("fail");
    }

    public function test if service not found()
    {
        $container = new Container();
        $this->expectException(NotFoundExceptionInterface::class);
        $container->get(Fail::class);
    }

    public function test if alias not found()
    {
        $container = new Container();
        $this->expectException(NotFoundExceptionInterface::class);
        $container->get(\DateTimeInterface::class);
    }
}
