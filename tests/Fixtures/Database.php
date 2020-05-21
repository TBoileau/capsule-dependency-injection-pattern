<?php

namespace TBoileau\DependencyInjection\Tests\Fixtures;

/**
 * Class Database
 * @package TBoileau\DependencyInjection\Tests\Fixtures
 */
class Database
{
    public function __construct(string $dbUrl, string $dbName, string $dbUser, string $dbPassword)
    {

    }
}
