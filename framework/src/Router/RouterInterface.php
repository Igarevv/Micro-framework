<?php

namespace Igarevv\Micrame\Router;

use Igarevv\Micrame\Http\Request;
use Psr\Container\ContainerInterface;

interface RouterInterface
{

    public function setRoutes(array $routes): void;

    public function dispatch(Request $request, ContainerInterface $container);

}