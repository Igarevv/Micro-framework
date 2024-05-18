<?php

namespace Igarevv\Micrame\Session;

interface SessionDataInterface
{
    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value): void;

    public function has(string $key): bool;

    public function clear(string $key): void;

    public function flash(string $type): array;

    public function setFlash(string $type, string|array $message): void;

    public function hasFlash(string $type): bool;

    public function clearFlash(string $type): void;
}