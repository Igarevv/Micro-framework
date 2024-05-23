<?php

namespace App\Application\UseCase\Book\UpdateBook;

use App\Domain\Based\Bus\Command\CommandInterface;

readonly class UploadImageCommand implements CommandInterface
{
    public function __construct(
      private int $bookId,
      private array $imageData
    ) {}

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getImageData(): array
    {
        return $this->imageData;
    }

}