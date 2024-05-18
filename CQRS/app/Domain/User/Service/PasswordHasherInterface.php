<?php

namespace App\Domain\User\Service;

use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\Password;

interface PasswordHasherInterface
{
    public function hash(Password $password): HashedPassword;

    public function verify(Password $password, HashedPassword $hashedPassword): bool;
}