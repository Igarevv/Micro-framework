<?php

namespace Igarevv\Micrame\Session;

class Session implements SessionInterface
{

    public const FLASH = 'flash';

    public function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->has($key)){
            return $_SESSION[$key];
        }

        return $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function clear(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function flash(string $type): array
    {
        $flash = $this->get(self::FLASH, []);

        if (isset($flash[$type])){
            $messages = $flash[$type];

            unset($flash[$type]);

            $this->set(self::FLASH, $flash);

            return $messages;
        }

        return [];
    }

    public function setFlash(string $type, string $message): void
    {
        $flash = $this->get(self::FLASH, []);

        $flash[$type][] = $message;

        $this->set(self::FLASH, $flash);
    }

    public function hasFlash(string $type): bool
    {
        return isset($_SESSION[self::FLASH][$type]);
    }

    public function clearFlash(string $type): void
    {
        unset($_SESSION[self::FLASH]);
    }

}