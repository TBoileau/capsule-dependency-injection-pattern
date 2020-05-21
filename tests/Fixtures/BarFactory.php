<?php

namespace TBoileau\DependencyInjection\Tests\Fixtures;

/**
 * Class BarFactory
 * @package TBoileau\DependencyInjection\Tests\Fixtures
 */
class BarFactory
{
    /**
     * @param Foo $foo
     * @return Bar
     */
    public static function create(Foo $foo): Bar
    {
        return new Bar($foo);
    }
}
