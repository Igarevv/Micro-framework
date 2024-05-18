<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Mappers\UserMapper;
use App\Domain\User\Entity\UserEntity;
use App\Infrastructure\Persistence\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\Based\ValueObject\Email;
use App\Infrastructure\Services\EntityManager\Contracts\EntityManagerServiceInterface;

class UserRepository implements UserRepositoryInterface
{

    public function __construct(
      private readonly EntityManagerServiceInterface $entityManagerService,
      private readonly UserMapper $mapper
    ) {}

    public function save(User $user): void
    {
        $this->entityManagerService->sync($user);
    }

    public function getByEmail(Email $email): UserEntity|false
    {
        $user = $this->entityManagerService->getRepository(User::class)
          ->findOneBy(['email' => $email->value()]);

        return $user ? $this->mapper->toDomain($user) : false;
    }

    public function isUserExists(Email $email): bool
    {
        $user = $this->entityManagerService
          ->getRepository(User::class)
          ->findOneBy(['email' => $email->value()]);

        if ($user){
            return true;
        }

        return false;
    }

    public function getById(int $id): UserEntity|false
    {
        $user = $this->entityManagerService->getRepository(User::class)
          ->findOneBy(['id' => $id]);

        return $user ? $this->mapper->toDomain($user) : false;
    }

}