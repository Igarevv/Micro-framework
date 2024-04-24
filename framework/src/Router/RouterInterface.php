<?php

namespace Igarevv\Micrame\Router;

use Igarevv\Micrame\Http\Request\RequestInterface;
use Psr\Container\ContainerInterface;

interface RouterInterface
{

    public function setRoutes(array $routes): void;

    public function dispatch(RequestInterface $request, ContainerInterface $container);

}