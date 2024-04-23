<?php

namespace Igarevv\Micrame\Http\Middleware;

use Igarevv\Micrame\Enums\HttpEnum;
use Igarevv\Micrame\Http\Middleware\Default\RouteMiddleware;
use Igarevv\Micrame\Http\Middleware\Default\SessionMiddleware;
use Igarevv\Micrame\Http\Request\RequestInterface;
use Igarevv\Micrame\Http\Response\Response;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{

    private array $middlewares = [
      SessionMiddleware::class,
      RouteMiddleware::class
    ];

    public function __construct(
      private ContainerInterface $container
    ) {}

    public function handle(RequestInterface $request): Response
    {
        if (! $this->middlewares){
            return new Response(HttpEnum::SERVER_ERROR->toString(), HttpEnum::SERVER_ERROR->value);
        }

        $middlewareClass = array_shift($this->middlewares);

        /** @var MiddlewareInterface $middlewareInstance */
        $middlewareInstance = $this->container->get($middlewareClass);

        return $middlewareInstance->process($request, $this);
    }

}