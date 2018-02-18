<?php declare(strict_types=1);

namespace Qlimix\MiddlewareRouter\Locator;

use Qlimix\MiddlewareRouter\Locator\Exception\LocatorException;

interface RouteLocatorInterface
{
    /**
     * @param string $route
     *
     * @return MiddlewareCollection
     *
     * @throws LocatorException
     */
    public function locate(string $route): MiddlewareCollection;
}
