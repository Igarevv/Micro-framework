<?php

namespace App\Infrastructure\Services\Session;

use Igarevv\Micrame\Session\SessionInterface;

class FlashMessageHandler
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function setError(string $key, string $message, array $data = []): void
    {
        $this->session->setFlash($key, array_merge(['error' => $message], $data));
    }

    public function setSuccess(string $key, string $message, array $data = []): void
    {
        $this->session->setFlash($key, array_merge(['success' => $message], $data));
    }

}