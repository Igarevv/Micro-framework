<?php

namespace App\Infrastructure\Services;

use App\Application\DTO\ImageDto;
use App\Domain\Book\Exception\ImageException;
use App\Domain\Book\Service\ImageServiceInterface;
use Ramsey\Uuid\Uuid;

class ImageService implements ImageServiceInterface
{

    private string $fileName;

    private string $extension;

    /**
     * @throws \App\Domain\Book\Exception\ImageException
     */
    public function createUniqueImage(array $imageData): ImageDto
    {
        if (! $imageData['name']){
            throw ImageException::undefinedImage('File not provided');
        }

        $this->fileName = $imageData['name'];

        $this->ensureImageIsValid();

        return new ImageDto(
          name:  Uuid::uuid4()->toString(),
          tmpPath: $imageData['tmp_name'],
          type: $this->extension
        );
    }

    protected function ensureImageIsValid(): void
    {
        $allowedExtension = ['jpeg', 'png', 'jpg'];

        $this->extension = pathinfo($this->fileName, PATHINFO_EXTENSION);

        if (! in_array($this->extension, $allowedExtension, true)) {
            throw ImageException::fileExtensionNotAllowed('Only jpeg, png, jpg files allowed');
        }
    }

}