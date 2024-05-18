<?php

namespace App\Domain\User\ValueObject;

use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Based\ValueObject\StringValue;

final class HashedPassword extends StringValue
{

    private const BCRYPT_PATTERN = '/^\$2[ayb]\$.{56}$/';

    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    private function ensureIsBcryptPattern(): void
    {
        $hashedPassword = $this->value;

        if (!preg_match(self::BCRYPT_PATTERN, $hashedPassword)) {
            throw new InvalidFormat('The hashed password isn\'t bcrypt encoded format');
        }
    }

}