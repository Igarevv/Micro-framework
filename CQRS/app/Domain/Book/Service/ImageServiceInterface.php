<?php

namespace App\Domain\Book\Service;

use App\Application\DTO\ImageDto;

interface ImageServiceInterface
{
    public function createUniqueImage(array $imageData): ImageDto;

}