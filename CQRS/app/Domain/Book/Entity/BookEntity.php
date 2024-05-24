<?php

namespace App\Domain\Book\Entity;

class BookEntity
{
    public const BOOK_ID = 'bookId';

    public const TITLE   = 'title';

    public const DESCRIPTION = 'description';

    public const ISBN = 'isbn';

    public const YEAR = 'year';

    public const AUTHOR_NAME = 'authorName';

    public const GENRE = 'genre';

    public const CREATED_AT = 'createdAt';

    public const IMAGE = 'image';

    public function __construct(
      private readonly int $bookId,
      private readonly string $title,
      private readonly string $description,
      private readonly int $isbn,
      private readonly int $year,
      private readonly string $authorFirstName,
      private readonly string $authorLastName,
      private readonly string $genre,
      private readonly string $createdAt,
      private ?string $image,
    ) {}

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIsbn(): int
    {
        return $this->isbn;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getAuthorFirstName(): string
    {
        return $this->authorFirstName;
    }

    public function getAuthorLastName(): string
    {
        return $this->authorLastName;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getFullAuthorName(): string
    {
        return "{$this->authorFirstName} {$this->authorLastName}";
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setHttpImageUrl(string $imageUrl): void
    {
        $this->image = $imageUrl;
    }

}