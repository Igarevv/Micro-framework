<?php

namespace App\Repository\Interfaces;

use App\DTO\Image;

interface ImageRepositoryInterface
{
    public function save(Image $image);

    public function delete(string $imageId);

    public function getImagesUrl(array $ids);
}