<?php

namespace App\Presentation\Middlewares;

use Igarevv\Micrame\Http\Middleware\MiddlewareInterface;
use Igarevv\Micrame\Http\Middleware\RequestHandlerInterface;
use Igarevv\Micrame\Http\Request\Request;
use Igarevv\Micrame\Http\Response\RedirectResponse;
use Igarevv\Micrame\Http\Response\Response;
use Igarevv\Micrame\Session\AuthSession;
use Igarevv\Micrame\Session\SessionInterface;

readonly class Guest implements MiddlewareInterface
{
    public function __construct(
      private AuthSession $auth,
      private SessionInterface $session
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