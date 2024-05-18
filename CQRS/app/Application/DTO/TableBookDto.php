<?php

namespace App\Application\DTO;

readonly class TableBookDto
{
    public function __construct(
      private int $bookId,
      private string $title,
      private string $authorFirstName,
      private string $authorLastName,
      private string $createdAt
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthorFirstName(): string
    {
        return $this->authorFirstName;
    }

    public function getAuthorLastName(): string
    {
        return $this->authorLastName;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

}