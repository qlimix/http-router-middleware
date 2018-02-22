<?php declare(strict_types=1);

namespace Qlimix\MiddlewareRouter\Locator;

use Psr\Container\ContainerInterface;
use Qlimix\HttpRequestHandler\Middleware\MiddlewareStack;
use Qlimix\MiddlewareRouter\Locator\Exception\LocatorException;

final class PSRContainerLocator implements RouteLocatorInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function locate(string $route): MiddlewareStack
    {
        try {
            return $this->container->get($route);
        } catch (\Throwable $exception) {
            throw new LocatorException('Could not find '.$route, 0, $exception);
        }
    }
}
