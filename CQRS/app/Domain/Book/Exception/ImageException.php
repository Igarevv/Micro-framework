<?php

namespace App\Domain\Book\Exception;

use Exception;

class ImageException extends Exception
{
    public static function undefinedImage(string $message, int $code = 0): static
    {
        return new static($message, $code);
    }

    public static function fileExtensionNotAllowed(string $message, int $code = 0): static
    {
        return new static($message, $code);
    }

}