<?php declare(strict_types=1);

namespace Qlimix\MiddlewareRouter\Aura;

use Aura\Router\RouterContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\HttpRequestHandler\Exception\HttpRequestHandlerException;
use Qlimix\HttpRequestHandler\MiddlewareHttpRequestHandler;
use Qlimix\MiddlewareRouter\Exception\RouteNotFoundException;
use Qlimix\MiddlewareRouter\Locator\Exception\LocatorException;
use Qlimix\MiddlewareRouter\Locator\RouteLocatorInterface;
use Qlimix\MiddlewareRouter\Middleware\ParentRequestHandlerMiddleware;

final class AuraMiddlewareRouter implements MiddlewareInterface
{
    /** @var RouterContainer */
    private $routeContainer;

    /** @var RouteLocatorInterface */
    private $locator;

    /**
     * @param RouterContainer $routeContainer
     * @param RouteLocatorInterface $locator
     */
    public function __construct(RouterContainer $routeContainer, RouteLocatorInterface $locator)
    {
        $this->routeContainer = $routeContainer;
        $this->locator = $locator;
    }

    /**
     * @inheritDoc
     *
     * @throws RouteNotFoundException
     * @throws LocatorException
     * @throws HttpRequestHandlerException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->routeContainer->getMatcher()->match($request);

        if (!$route) {
            throw new RouteNotFoundException('Route not found');
        }

        $middleware = $this->locator->locate($route->handler);

        foreach ($route->attributes as $key => $val) {
            $request = $request->withAttribute($key, $val);
        }

        $middleware->push(new ParentRequestHandlerMiddleware($handler));

        return (new MiddlewareHttpRequestHandler($middleware))->handle($request);
    }
}
