<?php

namespace App\Application\DTO;

use App\Domain\Based\ValueObject\FirstName;
use App\Domain\Based\ValueObject\LastName;
use App\Domain\Book\ValueObject\Isbn;
use App\Domain\Book\ValueObject\Title;
use App\Domain\Book\ValueObject\Year;

readonly class TableBookDto
{
    public function __construct(
      private int $bookId,
      private Title $title,
      private FirstName $authorFirstName,
      private LastName $authorLastName,
      private Isbn $isbn,
      private Year $year,
      private \DateTimeImmutable $createdAt
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthorFirstName(): string
    {
        return $this->authorFirstName->value();
    }

    public function getAuthorLastName(): string
    {
        return $this->authorLastName->value();
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getIsbn(): string
    {
        return $this->isbn->value();
    }

    public function getYear(): int
    {
        return $this->year->value();
    }

}