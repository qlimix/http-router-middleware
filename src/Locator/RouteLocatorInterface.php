<?php declare(strict_types=1);

namespace Qlimix\MiddlewareRouter\Locator;

use Qlimix\RequestHandler\Middleware\MiddlewareStack;
use Qlimix\MiddlewareRouter\Locator\Exception\LocatorException;

interface RouteLocatorInterface
{
    /**
     * @param string $route
     *
     * @return MiddlewareStack
     *
     * @throws LocatorException
     */
    public function locate(string $route): MiddlewareStack;
}
