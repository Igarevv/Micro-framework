<?php

namespace App\Domain\Based\ValueObject;

use App\Domain\Based\Exception\InvalidFormat;

final class Email extends StringValue
{
    private function __construct(protected string $value)
    {
        $this->ensureIsValidEmail($value);

        parent::__construct($this->value);
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    private function ensureIsValidEmail(string $email): void
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidFormat(sprintf('The email "%s" is not valid.', $email));
        }
    }

}