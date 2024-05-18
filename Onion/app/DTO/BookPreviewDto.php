<?php

namespace App\DTO;

class BookPreviewDto
{

    public function __construct(
      public readonly string $title,
      public readonly string $firstName,
      public readonly string $lastName,
      public readonly string $imageUrl,
      public readonly int $bookId
    ) {}

    public function getFullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

}