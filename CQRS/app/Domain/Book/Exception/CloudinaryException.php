<?php

namespace App\Domain\Book\Exception;

use Exception;

class CloudinaryException extends Exception
{

    public static function cloudinaryServiceError(): static
    {
        return new static('Error uploading image on Cloudinary. Please contact to your devs.', 500);
    }
}