<?php

namespace App\Application\UseCase\Book\CreateBook;

use App\Domain\Based\Bus\Command\CommandInterface;

readonly class CreateBookCommand implements CommandInterface
{
    public function __construct(
      private array $bookData,
      private string $imageId
    ) {}

    public function getTitle(): string
    {
        return $this->bookData['title'];
    }

    public function getDescription(): string
    {
        return $this->bookData['description'];
    }

    public function getYear(): string
    {
        return $this->bookData['year'];
    }

    public function getAuthorFirstName(): string
    {
        return $this->bookData['first_name'];
    }

    public function getAuthorLastName(): string
    {
        return $this->bookData['last_name'];
    }

    public function getGenres(): array
    {
        return $this->bookData['genre'];
    }

    public function getImageId(): string
    {
        return $this->imageId;
    }

    public function getIsbn(): string
    {
        return $this->bookData['isbn'];
    }

}