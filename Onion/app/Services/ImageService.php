<?php

namespace App\Services;

use App\DTO\Image;
use App\Exceptions\ImageException;
use App\Repository\Interfaces\ImageRepositoryInterface;
use Ramsey\Uuid\Uuid;

class ImageService
{

    public function __construct(
      protected ImageRepositoryInterface $repository
    ) {}

    public function uploadImage(Image $image): bool
    {
        $response = $this->repository->save($image);
        if (! $response){
            throw new ImageException('Service Cloudinary unavailable', 500);
        }
        return true;
    }

    public function deleteImage(string $imageId): bool
    {
        if ($this->repository->delete($imageId)){
            throw new ImageException('Image was not deleted');
        }

        return true;
    }

    public function getImageUrls(array $imageId)
    {
        return $this->repository->getImagesUrl($imageId);
    }

    public function imageDto(array $imageData): Image
    {
        if ( ! $this->allowedExtension($imageData['name'])) {
            throw new ImageException("Please choose file with image file type",
              400);
        }

        $extension = pathinfo($imageData['name'], PATHINFO_EXTENSION);

        $imageId = Uuid::uuid4()->toString();

        return new Image(
          name: $imageId,
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