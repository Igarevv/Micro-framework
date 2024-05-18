<?php

namespace App\Repository\Interfaces;

use App\DTO\User;

interface UserRepositoryInterface
{

    public function save(User $user): int;

    public function findById(int $id): User|false;

    public function findByEmail(string $email): User|false;

}