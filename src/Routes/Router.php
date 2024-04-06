<?php

namespace Ihor\MicroFramework\Routes;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Ihor\MicroFramework\Http\Exceptions\HttpNotFoundException;
use Ihor\MicroFramework\Http\Exceptions\MethodNotAllowedException;
use Ihor\MicroFramework\Http\Request;

use function FastRoute\simpleDispatcher;

class Router implements RouteInterface
{
    public function dispatch(Request $request): array
    {
        [$handler, $variables] = $this->routeInfo($request);

        if(is_array($handler)){
            [$controller, $method] = $handler;
            $handler = [new $controller, $method];
        }

        return [$handler, $variables];
    }

    private function routeInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector){
            $routes = require APP_PATH . '/config/web.php';

            foreach ($routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUriPath());

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