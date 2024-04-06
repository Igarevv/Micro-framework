<?php

namespace Igarevv\MicroFramework\Routes;

use Igarevv\MicroFramework\Http\Request;
use League\Container\Container;

interface RouteInterface
{

    public function registerRoutes(array $routes): void;

    public function dispatch(Request $request, Container $container);

}