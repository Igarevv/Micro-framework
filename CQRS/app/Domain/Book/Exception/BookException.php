<?php

namespace App\Domain\Book\Exception;

use Exception;

class BookException extends Exception
{
    public static function booksNotFound(): static
    {
        return new static('Books not found');
    }
}