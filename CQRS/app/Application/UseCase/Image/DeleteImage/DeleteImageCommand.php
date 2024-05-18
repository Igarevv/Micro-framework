<?php

namespace App\Application\UseCase\Image\DeleteImage;

use App\Domain\Based\Bus\Command\CommandInterface;

class DeleteImageCommand implements CommandInterface
{
    public function __construct(
      private readonly int $bookId
    ) {}

    public function getId(): int
    {
        return $this->bookId;
    }

}