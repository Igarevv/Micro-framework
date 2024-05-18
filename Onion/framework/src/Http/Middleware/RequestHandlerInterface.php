<?php

namespace Igarevv\Micrame\Http\Middleware;

use Igarevv\Micrame\Http\Request\RequestInterface;
use Igarevv\Micrame\Http\Response\Response;

interface RequestHandlerInterface
{

    public function handle(RequestInterface $request): Response;

    public function addMiddleware(array $middleware);

}