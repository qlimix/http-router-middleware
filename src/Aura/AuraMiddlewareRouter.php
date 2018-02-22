<?php declare(strict_types=1);

namespace Qlimix\MiddlewareRouter\Aura;

use Aura\Router\RouterContainer;
use Psr\Http\Message\ServerRequestInterface;
use Qlimix\HttpRequestHandler\Middleware\MiddlewareStack;
use Qlimix\MiddlewareRouter\Exception\RouteNotFoundException;
use Qlimix\MiddlewareRouter\Exception\RouterException;
use Qlimix\MiddlewareRouter\Locator\RouteLocatorInterface;
use Qlimix\MiddlewareRouter\MiddlewareRouterInterface;

final class AuraMiddlewareRouter implements MiddlewareRouterInterface
{
    /** @var RouterContainer */
    private $routeContainer;

    /** @var RouteLocatorInterface */
    private $routeLocator;

    /**
     * @param RouterContainer $routeContainer
     */
    public function __construct(RouterContainer $routeContainer)
    {
        $this->routeContainer = $routeContainer;
    }

    /**
     * @inheritDoc
     */
    public function route(ServerRequestInterface $request): MiddlewareStack
    {
        $route = $this->routeContainer->getMatcher()->match($request);

        if (!$route) {
            throw new RouteNotFoundException('Route not found');
        }

        foreach ($route->attributes as $key => $val) {
            $request = $request->withAttribute($key, $val);
        }

        try {
            return $this->routeLocator->locate($route->handler);
        } catch (\Exception $exception) {
            throw new RouterException('Could not locate middleware stack', 0, $exception);
        }
    }
}
