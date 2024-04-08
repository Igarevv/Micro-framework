<?php

namespace Igarevv\Micrame\Router;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Igarevv\Micrame\Exceptions\Http\HttpNotFoundException;
use Igarevv\Micrame\Exceptions\Http\MethodNotAllowedException;
use Igarevv\Micrame\Http\Request;

use Psr\Container\ContainerInterface;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{

    private array $routes = [];

    public function dispatch(
      Request $request,
      ContainerInterface $container
    ): array {
        [$handler, $args] = $this->routeInfo($request);

        if (is_array($handler)) {
            [$controllerName, $method] = $handler;

            $controller = $container->get($controllerName);

            $handler = [$controller, $method];
        }

        return [$handler, $args];
    }

    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    private function routeInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->method(), $request->uri());

        [$controllerInfo, $args] = $this->findRouteOrFail($routeInfo);

        return [$controllerInfo, $args];
    }

    private function findRouteOrFail(array $routeInfo): array
    {
        $status = $routeInfo[0];
        switch ($status) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $message = '405 Method Not Allowed';
                throw new MethodNotAllowedException($message, 405);
            default:
                throw new HttpNotFoundException('404 | Not Found', 404);
        }
    }

}