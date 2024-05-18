<?php

namespace App\Domain\User\ValueObject;

use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Based\ValueObject\StringValue;

class Password extends StringValue
{

    private const MIN_LENGTH = 5;

    protected function __construct(
      string $password,
    ) {
        parent::__construct($password);

        $this->ensureIsStrength();
    }

    private function ensureIsStrength(): void
    {
        $password = $this->value;

        if (! $this->hasValidLength($password)) {
            throw new InvalidFormat('The password is not enough strength, should contain minimum 5');
        }
    }

    private function hasValidLength(string $password): bool
    {
        return strlen($password) >= self::MIN_LENGTH;
    }

}