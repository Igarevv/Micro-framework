<?php

namespace App\Application\UseCase\User\GetUserById;

use App\Domain\Based\Bus\Query\QueryInterface;

class GetUserByIdQuery implements QueryInterface
{
    public function __construct(
      private readonly int $id
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

}