<?php

namespace App\Http\Middlewares;

use Igarevv\Micrame\Http\Middleware\MiddlewareInterface;
use Igarevv\Micrame\Http\Middleware\RequestHandlerInterface;
use Igarevv\Micrame\Http\Request\Request;
use Igarevv\Micrame\Http\Response\RedirectResponse;
use Igarevv\Micrame\Http\Response\Response;
use Igarevv\Micrame\Session\AuthSession;
use Igarevv\Micrame\Session\SessionInterface;

class Guest implements MiddlewareInterface
{
    public function __construct(
      private readonly AuthSession $auth,
      private readonly SessionInterface $session
    ) {}

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        if ($this->auth->isAuth()){
            return new RedirectResponse('/');
        }

        return $handler->handle($request);
    }

}