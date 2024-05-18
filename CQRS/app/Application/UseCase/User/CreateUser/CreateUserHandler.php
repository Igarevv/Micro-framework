<?php

namespace App\Application\UseCase\User\CreateUser;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Based\ValueObject\Email;
use App\Domain\Based\ValueObject\FirstName;
use App\Domain\Based\ValueObject\LastName;
use App\Domain\User\Exception\AuthException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Password;
use App\Infrastructure\Persistence\Entity\User;
use App\Infrastructure\Services\PasswordHasher;

class CreateUserHandler implements CommandHandlerInterface
{

    public function __construct(
      private readonly PasswordHasher $passwordHasher,
      private readonly UserRepositoryInterface $repository
    ) {}

    /**
     * @param CreateUserCommand $command
     * @throws \App\Domain\User\Exception\AuthException
     */
    public function handle(CommandInterface $command): void
    {
        $email = Email::fromString($command->email());

        $password = Password::fromString($command->password());

        $this->passwordConfirmIsValid($password, $command->confirmPassword());

        $this->isEmailExists($email);

        $user = User::create(
          firstName: FirstName::fromString($command->firstName()),
          lastName: LastName::fromString($command->lastName()),
          email: $email,
        );

        $user->setHashedPassword($this->passwordHasher->hash($password));

        $this->repository->save($user);
    }

    private function passwordConfirmIsValid(
      Password $password,
      string $passwordConfirm
    ): void {
        if ( ! $password->isEqual($passwordConfirm)) {
            throw AuthException::PasswordsNotSame('Passwords are not same');
        }
    }

    private function isEmailExists(Email $email): void
    {
        if ($this->repository->isUserExists($email)) {
            throw AuthException::SameUserExist('User with this email is already exists');
        }
    }

}