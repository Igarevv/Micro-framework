<?php

namespace App\Infrastructure\Services;

use App\Domain\User\Service\PasswordHasherInterface;
use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\Password;

class PasswordHasher implements PasswordHasherInterface
{

    public function hash(Password $password): HashedPassword
    {
        return HashedPassword::fromString(password_hash($password->value(), PASSWORD_BCRYPT));
    }

    public function verify(
      Password $password,
      HashedPassword $hashedPassword
    ): bool {
        return password_verify($password->value(), $hashedPassword->value());
    }

}