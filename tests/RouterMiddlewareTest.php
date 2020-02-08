<?php declare(strict_types=1);

namespace Qlimix\Test\Http\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\Http\Exception\InternalServerErrorException;
use Qlimix\Http\Exception\NotFoundException;
use Qlimix\Http\Middleware\RouterMiddleware;
use Qlimix\Http\Router\Exception\RouteNotFoundException;
use Qlimix\Http\Router\HttpRouterInterface;
use Qlimix\Http\Router\Locator\Exception\LocatorException;
use Qlimix\Http\Router\Locator\LocatorInterface;
use Qlimix\Http\Router\Parameter;
use Qlimix\Http\Router\Route;

final class RouterMiddlewareTest extends TestCase
{
    private MockObject $router;

    private MockObject $locator;

    private RouterMiddleware $middleware;

    private MockObject $request;

    private MockObject $handler;

    protected function setUp(): void
    {
        $this->router = $this->createMock(HttpRouterInterface::class);
        $this->locator = $this->createMock(LocatorInterface::class);

        $this->middleware = new RouterMiddleware($this->router, $this->locator);

        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);
    }

    public function testShouldRoute(): void
    {
        $this->router->expects($this->once())
            ->method('route')
            ->willReturn(new Route('handler', [new Parameter('key', 'value')]));

        $this->locator->expects($this->once())
            ->method('locate')
            ->willReturn($this->handler);

        $this->request->expects($this->once())
            ->method('withAttribute')
            ->willReturn($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    public function testShouldThrowOnRouteNotFound(): void
    {
        $this->router->expects($this->once())
            ->method('route')
            ->willThrowException(new RouteNotFoundException());

        $this->expectException(NotFoundException::class);

        $this->middleware->process($this->request, $this->handler);
    }

    public function testShouldThrowOnLocatorException(): void
    {
        $this->router->expects($this->once())
            ->method('route')
            ->willReturn(new Route('handler', []));

        $this->locator->expects($this->once())
            ->method('locate')
            ->willThrowException(new LocatorException());

        $this->expectException(InternalServerErrorException::class);

        $this->middleware->process($this->request, $this->handler);
    }
}
