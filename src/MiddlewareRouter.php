<?php declare(strict_types=1);

namespace Qlimix\HttpMiddleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\Router\Exception\RouteNotFoundException;
use Qlimix\Router\Exception\RouterException;
use Qlimix\Router\RouterInterface;

final class MiddlewareRouter implements MiddlewareInterface
{
    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @throws RouteNotFoundException
     * @throws RouterException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->router->route($request)->handle();
    }
}
