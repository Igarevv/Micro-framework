<?php

namespace App\Repository;

use App\DTO\Image;
use App\Repository\Interfaces\ImageRepositoryInterface;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;

class ImageCloudinaryRepository extends AbstractRepository implements ImageRepositoryInterface
{

    public function __construct(
      private Cloudinary $cloudinary
    ) {}

    public function save(Image $image): bool
    {
        $response = $this->cloudinary->uploadApi()->upload($image->getPath(), [
          'public_id' => $image->getFileName()
        ]);
        return $response->headers['Status'][0] === '200 OK';
    }

    public function delete(string $imageId): bool
    {
        $result = (new AdminApi($this->cloudinary->configuration))->deleteAssets($imageId, [
          "resource_type" => "image",
          "type" => "upload"
        ]);
        return $result->headers['Status'][0] === '200 OK';
    }

}