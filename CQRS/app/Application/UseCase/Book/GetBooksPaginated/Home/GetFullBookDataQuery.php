<?php

namespace App\Application\UseCase\Book\GetBooksPaginated\Home;

use App\Domain\Based\Bus\Query\QueryInterface;

class GetFullBookDataQuery implements QueryInterface
{
    public function __construct(
      private string $bookUrlId
    ) {}

    public function getBookUrlId(): string
    {
        return $this->bookUrlId;
    }
}