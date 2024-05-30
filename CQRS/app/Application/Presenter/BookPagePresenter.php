<?php

namespace App\Application\Presenter;

use App\Domain\Book\Entity\BookEntity;
use App\Domain\Book\ValueObject\Title;
use stdClass;

class BookPagePresenter implements Presenter
{

    public function __construct(
      private BookEntity $book,
    ) {}

    public function toBase(): stdClass
    {
        $book = new stdClass();

        $book->title = $this->book->getTitle();
        $book->year = $this->book->getYear();
        $book->isbn = $this->book->getIsbn();
        $book->genre = $this->book->getGenre();
        $book->image = $this->book->getImage();
        $book->tag = Title::fromString($book->title)->toUrlFormat();
        $book->authorName = $this->book->getFullAuthorName();
        $book->description = $this->book->getDescription();

        return $book;
    }

    public function toArray(): array
    {
        return [
          'tag' => Title::fromString($this->book->getTitle())->toUrlFormat(),
          BookEntity::IMAGE => $this->book->getImage(),
          BookEntity::TITLE => $this->book->getTitle(),
          BookEntity::ISBN  => $this->book->getIsbn(),
          BookEntity::YEAR  => $this->book->getYear(),
          BookEntity::DESCRIPTION => $this->book->getDescription(),
          BookEntity::AUTHOR_NAME => $this->book->getFullAuthorName(),
        ];
    }

}