<?php

namespace Igarevv\MicroFramework\Routes;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Igarevv\MicroFramework\Http\Exceptions\HttpNotFoundException;
use Igarevv\MicroFramework\Http\Exceptions\MethodNotAllowedException;
use Igarevv\MicroFramework\Http\Request;
use League\Container\Container;

use function FastRoute\simpleDispatcher;

class Router implements RouteInterface
{

    private array $routes;

    public function dispatch(Request $request, Container $container): array
    {
        [$handler, $variables] = $this->routeInfo($request);

        if (is_array($handler)) {
            [$controllerName, $method] = $handler;

            $controller = $container->get($controllerName);

            $handler = [new $controller(), $method];
        }

        return [$handler, $variables];
    }

    public function registerRoutes(array $routes): void
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

        $routeInfo = $dispatcher->dispatch($request->getMethod(),
          $request->getUriPath());

        [$handler, $variables] = $this->giveRouteInfoOrFail($routeInfo);

        return [$handler, $variables];
    }

    private function giveRouteInfoOrFail($routeInfo): array
    {
        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $message = '405 | Method not allowed';
                throw new MethodNotAllowedException($message, 405);
            default:
                throw new HttpNotFoundException('404 | Not Found', 404);
        }
    }

}