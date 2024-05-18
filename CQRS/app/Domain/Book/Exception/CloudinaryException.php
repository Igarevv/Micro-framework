<?php

namespace App\Domain\Book\Exception;

use Exception;

class CloudinaryException extends Exception
{

    public static function cloudinaryServiceError(): static
    {
        return new static('Service Cloudinary is unavailable', 500);
    }
}