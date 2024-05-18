<?php

namespace App\Repository\Mappers;

use App\DTO\User;

class UserMapper
{

    public function convertDataToUser(array $data): User
    {
        return User::make(
          firstName: $data['first_name'],
          lastName: $data['last_name'],
          email: $data['email'],
          password: $data['password'] ?? null,
          id: $data['id'] ?? null,
          createdAt:  new \DateTimeImmutable($data['created_at'])
        );
    }

}