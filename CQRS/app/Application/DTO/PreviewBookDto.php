<?php

namespace App\Application\DTO;

use App\Domain\Based\ValueObject\FirstName;
use App\Domain\Based\ValueObject\LastName;
use App\Domain\Book\ValueObject\Title;

readonly class PreviewBookDto
{
    public function __construct(
      private int $id,
      private FirstName $firstName,
      private LastName $lastName,
      private Title $title,
      private string $imageId
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function getImageId(): string
    {
        return $this->imageId;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

}