<?php

namespace App\Application\UseCase\Book\GetBooksPaginated;

use App\Domain\Based\Bus\Query\QueryInterface;

class GetPaginatedBooksCommand implements QueryInterface
{
    public function __construct(
      private readonly ?array $params
    ) {}

    public function getParams(): ?array
    {
        return $this->params;
    }

}