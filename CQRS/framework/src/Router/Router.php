<?php

namespace Igarevv\Micrame\Router;

use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Request\Request;
use Psr\Container\ContainerInterface;

class Router implements RouterInterface
{

    public function dispatch(Request $request, ContainerInterface $container): array
    {
        [$handler, $args] = $request->getRouteHandlerAndArgs();

        if (is_array($handler)) {
            [$controllerName, $method] = $handler;

            $controller = $container->get($controllerName);

            if (is_subclass_of($controller, Controller::class)){
                $controller->setRequest($request);
            }

            $handler = [$controller, $method];
        }

        return [$handler, $args];
    }

}