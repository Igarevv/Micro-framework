<?php

namespace App\Application\Presenter;

use App\Domain\Book\Entity\BookEntity;
use App\Infrastructure\Persistence\Entity\Author;
use App\Infrastructure\Persistence\Entity\Book;
use App\Infrastructure\Persistence\Entity\BookAuthor;
use stdClass;

class TableBookPresenter implements Presenter
{

    private Book $book;

    private Author $author;

    public function __construct(private readonly BookAuthor $bookAuthor)
    {
        $this->book = $this->bookAuthor->getBook();
        $this->author = $this->bookAuthor->getAuthor();
    }

    public function toBase(): stdClass
    {
        $tableBookData = new stdClass();

        $tableBookData->title  = $this->book->getTitle()->value();
        $tableBookData->bookId = $this->book->getId();
        $tableBookData->authorName = $this->author->getFullName();
        $tableBookData->createdAt  = $this->bookAuthor->getDateTime()
          ->format('Y-m-d H:i:s');
        $tableBookData->year = $this->book->getYear()->value();
        $tableBookData->isbn = $this->book->getIsbn()->value();

        return $tableBookData;
    }

    public function toArray(): array
    {
        return [
           BookEntity::BOOK_ID => $this->book->getId(),
           BookEntity::TITLE   => $this->book->getTitle()->value(),
           BookEntity::ISBN    => $this->book->getIsbn()->value(),
           BookEntity::YEAR    => $this->book->getYear()->value(),
           BookEntity::AUTHOR_NAME => $this->author->getFullName(),
           BookEntity::CREATED_AT  => $this->bookAuthor->getDateTime()->format('Y-m-d H:i:s')
        ];
    }

}