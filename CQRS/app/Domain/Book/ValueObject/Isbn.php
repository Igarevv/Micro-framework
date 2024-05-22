<?php

namespace App\Domain\Book\ValueObject;

use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Based\ValueObject\IntegerValue;

class Isbn extends IntegerValue
{

    protected function __construct(int|string $value)
    {
        parent::__construct($value);

        $this->isValid();
    }

    private function isValid(): void
    {
        if (strlen($this->value) !== 13) {
            throw new InvalidFormat('ISBN must have 13 numbers');
        }
    }

}