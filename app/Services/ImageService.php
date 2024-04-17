<?php

namespace App\Services;

use App\DTO\Image;
use App\Exceptions\ImageException;

class ImageService
{

    public function uploadImage() {}

    public function image(array $imageData): Image
    {
        if ( ! $this->allowedExtension($imageData['name'])) {
            throw new ImageException("Please choose file with image file type",
              400);
        }

        $extension = pathinfo($imageData['name'], PATHINFO_EXTENSION);

        $id = uniqid('', true);
        $randInt = random_int(0, 1000);
        $newFileName = "{$id}{$randInt}";

        return new Image(
          name: $newFileName,
          tmpPath: $imageData['tmp_name'],
          type: $extension
        );
    }

    protected function allowedExtension(string $image): bool
    {
        $allowedExtension = ['jpeg', 'png', 'jpg'];
        $extension = pathinfo($image, PATHINFO_EXTENSION);

        if (in_array($extension, $allowedExtension, true)) {
            return true;
        }
        return false;
    }

}