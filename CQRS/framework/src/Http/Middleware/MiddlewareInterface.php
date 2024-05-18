<?php

namespace Igarevv\Micrame\Http\Middleware;

use Igarevv\Micrame\Http\Request\Request;
use Igarevv\Micrame\Http\Response\Response;

interface MiddlewareInterface
{

    public function process(Request $request, RequestHandlerInterface $handler): Response;

}