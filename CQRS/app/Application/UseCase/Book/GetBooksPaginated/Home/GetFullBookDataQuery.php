<?php

namespace App\Application\UseCase\Book\GetBooksPaginated\Home;

use App\Domain\Based\Bus\Query\QueryInterface;

class GetFullBookDataQuery implements QueryInterface
{
    public function __construct(
      private string $bookId,
      private string $bookTag
    ) {}

    public function getBookId(): string
    {
        return $this->bookId;
    }

    public function getBookTag(): string
    {
        return $this->bookTag;
    }

}