<?php

namespace App\Domain\Book\Entity;

readonly class BookEntity
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
      private int $bookId,
      private string $title,
      private string $description,
      private int $isbn,
      private int $year,
      private string $authorFirstName,
      private string $authorLastName,
      private array $genre,
      private string $createdAt
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

    public function getGenre(): array
    {
        return $this->genre;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

}