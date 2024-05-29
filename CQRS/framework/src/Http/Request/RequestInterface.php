<?php

namespace Igarevv\Micrame\Http\Request;

use Igarevv\Micrame\Session\SessionInterface;

interface RequestInterface
{

    public function getMethod(): string;

    public function getUri(): string;

    public function getPost(array|string $keys = []): mixed;

    public function getGet(array|string $keys = []): mixed;

    public function getFiles(?string $key = null): array;

    public function setSession(SessionInterface $session): void;

    public function session(): SessionInterface;

    public function getFile(?string $key = null): array;

    public function isXhr(): bool;
}