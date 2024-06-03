<?php

namespace App\Application\Presenter;

use App\Application\DTO\PreviewBookDto;
use App\Domain\Book\Entity\BookEntity;
use stdClass;

class HomePagePresenter implements Presenter
{

    public function __construct(
      private readonly PreviewBookDto $book,
      private readonly string $currentImageUrl
    ) {}

    public function toBase(): stdClass
    {
        $book = new stdClass();

        $book->bookId = $this->book->getId();
        $book->tag = $this->book->getTitle()->toUrlFormat();
        $book->authorName = "{$this->book->getFirstName()} {$this->book->getLastName()}";
        $book->title = $this->book->getTitle()->value();
        $book->imageUrl = $this->currentImageUrl;

        return $book;
    }

    public function toArray(): array
    {
        return [
          'tag'               => $this->book->getTitle()->toUrlFormat(),
          BookEntity::BOOK_ID => $this->book->getId(),
          BookEntity::TITLE   => $this->book->getTitle()->value(),
          BookEntity::IMAGE   => $this->currentImageUrl,
          BookEntity::AUTHOR_NAME => "{$this->book->getFirstName()} {$this->book->getLastName()}"
        ];
    }

}