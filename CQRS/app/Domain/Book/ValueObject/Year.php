<?php

namespace App\Domain\Book\ValueObject;

use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Based\ValueObject\IntegerValue;

class Year extends IntegerValue
{
    protected function __construct(int|string $value)
    {
        parent::__construct($value);

        $this->isFullYearFormat();
    }

    private function isFullYearFormat(): void
    {
        if (strlen($this->value) !== 4){
            throw new InvalidFormat('Year must have 4 numbers');
        }
    }
}