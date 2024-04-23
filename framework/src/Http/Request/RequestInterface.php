<?php

namespace Igarevv\Micrame\Http\Request;

use Igarevv\Micrame\Session\SessionInterface;

interface RequestInterface
{

    public function getMethod(): string;

    public function getUri(): string;

    public function getPost(array $keys = []): array;

    public function getGet(array $keys = []): array;

    public function getFiles(?string $key = null): array;

    public function setSession(SessionInterface $session): void;

    public function session(): SessionInterface;

}