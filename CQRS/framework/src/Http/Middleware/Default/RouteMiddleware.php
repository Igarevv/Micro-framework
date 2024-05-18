<?php

namespace Igarevv\Micrame\Http\Middleware\Default;

use Igarevv\Micrame\Http\Middleware\RequestHandlerInterface as Handler;
use Igarevv\Micrame\Http\Middleware\MiddlewareInterface;
use Igarevv\Micrame\Http\Response\Response;
use Igarevv\Micrame\Router\RouterInterface;
use Psr\Container\ContainerInterface;
use Igarevv\Micrame\Http\Request\Request;

readonly class RouteMiddleware implements MiddlewareInterface
{

    public function __construct(
      private ContainerInterface $container,
      private RouterInterface $router
    ) {}

    public function process(Request $request, Handler $handler): Response
    {
        [$routeHandler, $args] = $this->router->dispatch($request,
          $this->container);

        return call_user_func_array($routeHandler, $args);
    }

}