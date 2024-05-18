<?php

namespace Igarevv\Micrame\Router;

use Igarevv\Micrame\Http\Request\Request;
use Psr\Container\ContainerInterface;

interface RouterInterface
{

    public function dispatch(Request $request, ContainerInterface $container);

}