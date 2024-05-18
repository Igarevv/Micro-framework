<?php

namespace App\Application\UseCase\Image\UploadImage;

use App\Application\DTO\ImageDto;
use App\Domain\Based\Bus\Command\CommandInterface;

class UploadImageCommand implements CommandInterface
{
    public function __construct(
      private readonly ImageDto $imageDto
    ) {}

    public function getImageData(): ImageDto
    {
        return $this->imageDto;
    }

}