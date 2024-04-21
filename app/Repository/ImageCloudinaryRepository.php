<?php

namespace App\Repository;

use App\DTO\Image;
use App\Repository\Interfaces\ImageRepositoryInterface;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;

class ImageCloudinaryRepository extends AbstractRepository implements
  ImageRepositoryInterface
{

    private AdminApi $api;

    public function __construct(
      private Cloudinary $cloudinary
    ) {
        $this->api = $this->cloudinary->adminApi();
    }

    public function save(Image $image): bool
    {
        $response = $this->cloudinary->uploadApi()->upload($image->getPath(), [
          'public_id' => $image->getFileName(),
        ]);
        return $response->headers['Status'][0] === '200 OK';
    }

    public function delete(string $imageId): bool
    {
        $result = $this->api->deleteAssets($imageId, [
          "resource_type" => "image",
          "type" => "upload",
        ]);
        return $result->headers['Status'][0] === '200 OK';
    }

    public function getImage(string $id): string
    {
        $url = $this->cloudinary->image($id)
          ->resize(Resize::fill(400, 400))
          ->toUrl();

        return $url;
    }

}