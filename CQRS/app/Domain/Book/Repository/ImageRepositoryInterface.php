<?php

namespace App\Domain\Book\Repository;

use App\Application\DTO\ImageDto;

interface ImageRepositoryInterface
{
    public function upload(ImageDto $image): bool;

    public function delete(string $imageId): bool;

    public function getImagesUrl(array $ids): array;

    public function getOneImage(string $id): string|false;
}