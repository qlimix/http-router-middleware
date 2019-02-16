<?php declare(strict_types=1);

namespace Qlimix\HttpMiddleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\Http\Exception\InternalServerErrorException;
use Qlimix\Http\Exception\NotFoundException;
use Qlimix\HttpMiddleware\Exception\InvalidRouteHandlerException;
use Qlimix\Router\Exception\RouteNotFoundException;
use Qlimix\Router\RouterInterface;
use Throwable;

final class MiddlewareRouter implements MiddlewareInterface
{
    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
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
            $routeRequestHandler = $this->router->route($request);

            $handler = $routeRequestHandler->getHandler();
            if (!$handler instanceof RequestHandlerInterface) {
                throw new InvalidRouteHandlerException('Invalid handler expecting '.RequestHandlerInterface::class);
            }

            return $handler->handle($routeRequestHandler->getRequest());
        } catch (RouteNotFoundException $exception) {
            throw new NotFoundException();
        } catch (Throwable $exception) {
            throw new InternalServerErrorException($exception);
        }
    }
}
