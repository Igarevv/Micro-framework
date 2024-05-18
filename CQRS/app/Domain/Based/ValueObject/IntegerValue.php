<?php

namespace App\Domain\Based\ValueObject;

use App\Domain\Based\Exception\InvalidFormat;

abstract class IntegerValue
{
    protected function __construct(protected string|int $value) {}

    public static function fromStringToInt(string|int $value): static
    {
        if (! is_numeric($value)){
            throw new InvalidFormat("Value: {$value} must be numeric");
        }

        return new static((int) $value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public function isEqual(self|string|int $other): bool
    {
        return (int) $this === (int) $other;
    }

}