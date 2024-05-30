<?php

namespace App\Application\DTO;

use App\Domain\Based\ValueObject\FirstName;
use App\Domain\Based\ValueObject\LastName;
use App\Domain\Book\ValueObject\Isbn;
use App\Domain\Book\ValueObject\Title;
use App\Domain\Book\ValueObject\Year;

readonly class UploadCsvBooksDto
{
    public function __construct(
      public Title $title,
      public FirstName $firstName,
      public LastName $lastName,
      public Year $year,
      public Isbn $isbn,
      public string $description,
      public array $genre
    ) {}

}