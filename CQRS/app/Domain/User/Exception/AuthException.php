<?php

namespace App\Domain\User\Exception;

use Exception;

class AuthException extends Exception
{
    public static function UserNotFound(string $message, int $status = 0): static
    {
        return new static($message, $status);
    }

    public static function PasswordsNotSame(string $message, int $status = 0): static
    {
        return new static($message, $status);
    }

    public static function SameUserExist(string $message, int $status = 0): static
    {
        return new static($message, $status);
    }

}