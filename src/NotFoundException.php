<?php

namespace TBoileau\DependencyInjection;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class NotFoundException
 * @package TBoileau\DependencyInjection
 */
class NotFoundException extends Exception implements NotFoundExceptionInterface
{

}
