<?php

namespace TBoileau\DependencyInjection\Tests\Fixtures;

/**
 * Class Bar
 * @package TBoileau\DependencyInjection\Tests\Fixtures
 */
class Bar
{
    /**
     * @var Foo
     */
    public Foo $foo;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }
}
