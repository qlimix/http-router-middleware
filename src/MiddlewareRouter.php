<?php declare(strict_types=1);

namespace Qlimix\HttpMiddleware\Router;

use Psr\Http\Message\RequestInterface;
use Qlimix\HttpMiddleware\Router\Exception\RouteNotFoundException;
use Qlimix\HttpMiddleware\Router\Exception\RouterException;

interface MiddlewareRouter
{
    /**
     * @throws RouteNotFoundException
     * @throws RouterException
     */
    public function route(RequestInterface $request): RequestInterface;
}
