<?php

namespace App\Application\UseCase\User\GetUserById;

use App\Domain\Based\Bus\Query\QueryHandleInterface;
use App\Domain\Based\Bus\Query\QueryInterface;
use App\Domain\User\Entity\UserEntity;
use App\Domain\User\Repository\UserRepositoryInterface;

class GetUserByIdHandler implements QueryHandleInterface
{

    public function __construct(
      private readonly UserRepositoryInterface $repository
    ) {}

    public function handle(QueryInterface $command): UserEntity
    {
        return $this->repository->getById($command->getId());
    }

}