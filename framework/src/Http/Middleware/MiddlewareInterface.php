<?php

namespace Igarevv\Micrame\Http\Middleware;

use Igarevv\Micrame\Http\Request\RequestInterface;
use Igarevv\Micrame\Http\Response\Response;

interface MiddlewareInterface
{

    public function process(RequestInterface $request, RequestHandlerInterface $handler): Response;

}