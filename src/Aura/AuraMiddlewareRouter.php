<?php declare(strict_types=1);

namespace Qlimix\MiddlewareRouter\Aura;

use Aura\Router\RouterContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\HttpRequestHandler\Middleware\MiddlewareStack;
use Qlimix\HttpRequestHandler\MiddlewareRequestHandler;
use Qlimix\MiddlewareRouter\Exception\RouteNotFoundException;
use Qlimix\MiddlewareRouter\Exception\RouterException;
use Qlimix\MiddlewareRouter\Middleware\ParentRequestHandlerMiddleware;

final class AuraMiddlewareRouter implements MiddlewareInterface
{
    /** @var RouterContainer */
    private $routeContainer;

    /**
     * @param RouterContainer $routeContainer
     */
    public function __construct(RouterContainer $routeContainer)
    {
        $this->routeContainer = $routeContainer;
    }

    /**
     * @inheritDoc
     *
     * @throws RouterException
     * @throws RouteNotFoundException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->routeContainer->getMatcher()->match($request);

        if (!$route) {
            throw new RouteNotFoundException('Route not found');
        }

        $middleware = $route->handler;
        if (!$middleware instanceof MiddlewareStack) {
            throw new RouterException('Invalid handler path');
        }

        foreach ($route->attributes as $key => $val) {
            $request = $request->withAttribute($key, $val);
        }

        $middleware->push(new ParentRequestHandlerMiddleware($handler));

        return (new MiddlewareRequestHandler($middleware))->handle($request);
    }
}
