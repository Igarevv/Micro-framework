<?php

namespace App\Application\UseCase\Book\DeleteBook;

use App\Domain\Based\Bus\Command\CommandInterface;

readonly class DeleteBookCommand implements CommandInterface
{
    public function __construct(
      private int $bookId
    ) {}

    public function getId(): int
    {
        return $this->bookId;
    }
}