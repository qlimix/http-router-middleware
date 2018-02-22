<?php declare(strict_types=1);

namespace Qlimix\MiddlewareRouter;

use Psr\Http\Message\ServerRequestInterface;
use Qlimix\HttpRequestHandler\Middleware\MiddlewareStack;
use Qlimix\MiddlewareRouter\Exception\RouteNotFoundException;
use Qlimix\MiddlewareRouter\Exception\RouterException;

interface MiddlewareRouterInterface
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return MiddlewareStack
     *
     * @throws RouteNotFoundException
     * @throws RouterException
     */
    public function route(ServerRequestInterface $request): MiddlewareStack;
}
