<?php

namespace App\Application\Presenter;

use App\Domain\Book\Entity\BookEntity;
use App\Infrastructure\Persistence\Entity\Author;
use App\Infrastructure\Persistence\Entity\Book;
use App\Infrastructure\Persistence\Entity\BookAuthor;
use stdClass;

class HomePagePresenter implements Presenter
{

    private Book $book;

    private Author $author;

    public function __construct(
      private readonly BookAuthor $bookAuthor,
      private readonly string $currentImageUrl
    ) {
        $this->book = $this->bookAuthor->getBook();
        $this->author = $this->bookAuthor->getAuthor();
    }

    public function toBase(): stdClass
    {
        $book = new stdClass();

        $book->id = $this->book->getId();
        $book->authorName = $this->author->getFullName();
        $book->title = $this->book->getTitle();
        $book->imageUrl = $this->currentImageUrl;

        return $book;
    }

    public function toArray(): array
    {
        return [
          BookEntity::BOOK_ID => $this->book->getId(),
          BookEntity::TITLE   => $this->book->getTitle(),
          BookEntity::IMAGE   => $this->currentImageUrl,
          BookEntity::AUTHOR_NAME => $this->author->getFullName()
        ];
    }

}