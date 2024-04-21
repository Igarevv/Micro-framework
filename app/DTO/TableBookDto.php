<?php

namespace App\DTO;

class TableBookDto
{
    public function __construct(
      public readonly int $bookId,
      public readonly string $title,
      public readonly string $firstName,
      public readonly string $lastName,
      public readonly int $isbn,
      public readonly int $year,
      public readonly string $time
    ) {}

    public function getFullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}