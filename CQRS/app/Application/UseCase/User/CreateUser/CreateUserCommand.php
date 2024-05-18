<?php

namespace App\Application\UseCase\User\CreateUser;

use App\Domain\Based\Bus\Command\CommandInterface;

readonly class CreateUserCommand implements CommandInterface
{

    public function __construct(
      public array $userData
    ) {}

    public function firstName(): string
    {
        return $this->userData['firstName'];
    }

    public function lastName(): string
    {
        return $this->userData['lastName'];
    }

    public function email(): string
    {
        return $this->userData['email'];
    }

    public function password(): string
    {
        return $this->userData['password'];
    }

    public function confirmPassword(): string
    {
        return $this->userData['passwordConfirm'];
    }

}