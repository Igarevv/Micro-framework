<?php

namespace Igarevv\Micrame\Http\Middleware\Default;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Igarevv\Micrame\Exceptions\Http\HttpNotFoundException;
use Igarevv\Micrame\Exceptions\Http\MethodNotAllowedException;
use Igarevv\Micrame\Http\Middleware\MiddlewareInterface;
use Igarevv\Micrame\Http\Middleware\RequestHandlerInterface;
use Igarevv\Micrame\Http\Request\Request;
use Igarevv\Micrame\Http\Response\Response;

use function FastRoute\simpleDispatcher;

class RouteInfoMiddleware implements MiddlewareInterface
{

    public function __construct(
      private readonly array $routes
    ) {}

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri());

        $status = $routeInfo[0];
        switch ($status) {
            case Dispatcher::FOUND:
                $request->setRouteHandler($routeInfo[1][0]);

                $request->setRouteArgs($routeInfo[2]);

                $handler->addMiddleware($routeInfo[1][1]);

                return $handler->handle($request);
            case Dispatcher::METHOD_NOT_ALLOWED:
                $message = '405 Method Not Allowed';
                throw new MethodNotAllowedException($message, 405);
            default:
                throw new HttpNotFoundException('404 | Not Found', 404);
        }
    }

}