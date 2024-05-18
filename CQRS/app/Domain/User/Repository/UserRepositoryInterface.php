<?php

namespace App\Domain\User\Repository;

use App\Domain\Based\ValueObject\Email;
use App\Domain\User\Entity\UserEntity;
use App\Infrastructure\Persistence\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function getByEmail(Email $email): UserEntity|false;

    public function isUserExists(Email $email): bool;

    public function getById(int $id): UserEntity|false;
}