<?php

namespace Igarevv\Micrame\Session;

class AuthSession
{
    public function __construct(
      private readonly SessionInterface $session
    ) {}

    public function isAuth(): bool
    {
        return $this->session->has(Session::AUTH);
    }

    public function logout(): void
    {
        $this->session->clear(Session::AUTH);

        $this->session->close();
    }

}