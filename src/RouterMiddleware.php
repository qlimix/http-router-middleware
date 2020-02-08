<?php declare(strict_types=1);

namespace Qlimix\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\Http\Exception\InternalServerErrorException;
use Qlimix\Http\Exception\NotFoundException;
use Qlimix\Http\Router\Exception\RouteNotFoundException;
use Qlimix\Http\Router\HttpRouterInterface;
use Qlimix\Http\Router\Locator\LocatorInterface;
use Throwable;

final class RouterMiddleware implements MiddlewareInterface
{
    private HttpRouterInterface $router;

    private LocatorInterface $locator;

    public function __construct(HttpRouterInterface $router, LocatorInterface $locator)
    {
        $this->router = $router;
        $this->locator = $locator;
    }

    /**
     * @inheritdoc
     *
     * @throws NotFoundException
     * @throws InternalServerErrorException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $route = $this->router->route($request);

            foreach ($route->getParameters() as $parameter) {
                $request = $request->withAttribute($parameter->getKey(), $parameter->getValue());
            }

            return $this->locator->locate($route->getHandler())->handle($request);
        } catch (RouteNotFoundException $exception) {
            throw new NotFoundException();
        } catch (Throwable $exception) {
            throw new InternalServerErrorException($exception);
        }
    }
}
