<?php

namespace Igarevv\Micrame\Http\Middleware\Default;

use Igarevv\Micrame\Http\Middleware\MiddlewareInterface;
use Igarevv\Micrame\Http\Middleware\RequestHandlerInterface as Handler;
use Igarevv\Micrame\Http\Request\RequestInterface;
use Igarevv\Micrame\Http\Response\Response;
use Igarevv\Micrame\Session\SessionInterface;

class SessionMiddleware implements MiddlewareInterface
{

    public function __construct(
      private SessionInterface $session
    ) {}

    public function process(RequestInterface $request, Handler $handler): Response
    {
        $this->session->start();

        $request->setSession($this->session);

        return $handler->handle($request);
    }

}