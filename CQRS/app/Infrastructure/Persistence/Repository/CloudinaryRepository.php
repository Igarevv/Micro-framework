<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\DTO\ImageDto;
use App\Domain\Book\Repository\ImageRepositoryInterface;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Cloudinary;

class CloudinaryRepository implements ImageRepositoryInterface
{

    private AdminApi $api;

    public function __construct(
      private readonly Cloudinary $cloudinary
    ) {
        $this->api = $this->cloudinary->adminApi();
    }

    public function upload(ImageDto $image): bool
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

    public function getImagesUrl(array $ids): array
    {
        $urls = $this->api->assetsByIds($ids)->getArrayCopy();

        return $urls['resources'];
    }

    public function getOneImage(string $id): string|false
    {
        $url = $this->api->assetsByIds($id);

        if (! $url['resources']){
            return false;
        }

        return $url['resources'][0]['url'];
    }

}