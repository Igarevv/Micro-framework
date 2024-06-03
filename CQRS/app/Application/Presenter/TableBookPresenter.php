<?php

namespace App\Application\Presenter;

use App\Application\DTO\TableBookDto;
use App\Domain\Book\Entity\BookEntity;
use stdClass;

class TableBookPresenter implements Presenter
{
    public function __construct(private TableBookDto $tableBookDto) {}

    public function toBase(): stdClass
    {
        $tableBookData = new stdClass();

        $tableBookData->title = $this->tableBookDto->getTitle();
        $tableBookData->bookId = $this->tableBookDto->getBookId();
        $tableBookData->authorName =
          "{$this->tableBookDto->getAuthorFirstName()} {$this->tableBookDto->getAuthorLastName()}";
        $tableBookData->createdAt = $this->tableBookDto->getCreatedAt()
          ->format('Y-m-d H:i:s');
        $tableBookData->year = $this->tableBookDto->getYear();
        $tableBookData->isbn = $this->tableBookDto->getIsbn();

        return $tableBookData;
    }

    public function toArray(): array
    {
        return [
          BookEntity::BOOK_ID => $this->tableBookDto->getBookId(),
          BookEntity::TITLE => $this->tableBookDto->getTitle(),
          BookEntity::ISBN => $this->tableBookDto->getIsbn(),
          BookEntity::YEAR => $this->tableBookDto->getYear(),
          BookEntity::AUTHOR_NAME =>
            "{$this->tableBookDto->getAuthorFirstName()} {$this->tableBookDto->getAuthorLastName()}",
          BookEntity::CREATED_AT => $this->tableBookDto->getCreatedAt()
            ->format('Y-m-d H:i:s')
        ];
    }

}