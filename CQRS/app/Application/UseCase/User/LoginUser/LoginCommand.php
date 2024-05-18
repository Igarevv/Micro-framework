<?php

namespace App\Application\UseCase\User\LoginUser;

use App\Domain\Based\Bus\Command\CommandInterface;

readonly class LoginCommand implements CommandInterface
{
    public function __construct(
      private string $email,
      private string $password
    ) {}

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

}