<?php

namespace App\Domain\Book\ValueObject;

use App\Domain\Based\ValueObject\StringValue;

class Title extends StringValue
{
    public function __construct(
      protected string $value
    ) {
        parent::__construct($value);
    }

    public function toUrlFormat(): string
    {
        $doubleHyphen = preg_replace('/(?<!-)-(?!-)/', '--', $this->value);

        $noSpaces = preg_replace('/\s+/', '-', $doubleHyphen);

        return strtolower($noSpaces);
    }

    public function fromUrlFormat(): string
    {
        if (preg_match('/-{2,}/', $this->value)) {
            $string = preg_replace('/-{2,}/', '-', $this->value);
        } else {
            $string = str_replace('-', ' ', $this->value);
        }

        return $string;
    }

}