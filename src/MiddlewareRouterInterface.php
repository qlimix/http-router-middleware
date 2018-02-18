<?php declare(strict_types=1);

namespace Qlimix\MiddlewareRouter;

use Psr\Http\Message\ServerRequestInterface;
use Qlimix\MiddlewareRouter\Exception\RouteNotFoundException;
use Qlimix\MiddlewareRouter\Exception\RouterException;

interface MiddlewareRouterInterface
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return MiddlewareCollection
     *
     * @throws RouteNotFoundException
     * @throws RouterException
     */
    public function route(ServerRequestInterface $request): MiddlewareCollection;
}
