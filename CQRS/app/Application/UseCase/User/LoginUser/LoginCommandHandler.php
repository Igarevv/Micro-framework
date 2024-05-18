<?php

namespace App\Application\UseCase\User\LoginUser;

use App\Application\Presenter\UserPresenter;
use App\Application\UseCase\User\GetUserById\GetUserByIdHandler;
use App\Application\UseCase\User\GetUserById\GetUserByIdQuery;
use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Based\ValueObject\Email;
use App\Domain\User\Exception\AuthException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\Password;
use App\Infrastructure\Bus\Query\QueryBusInterface;
use App\Infrastructure\Services\PasswordHasher;
use stdClass;

class LoginCommandHandler implements CommandHandlerInterface
{

    public function __construct(
      private readonly UserRepositoryInterface $repository,
      private readonly PasswordHasher $passwordHasher,
      private readonly QueryBusInterface $queryBus
    ) {}

    /**
     * @param LoginCommand $command
     *
     * @throws \App\Domain\User\Exception\AuthException
     */
    public function handle(CommandInterface $command): stdClass
    {
        $user = $this->repository->getByEmail(
          Email::fromString($command->email())
        );

        if (! $user){
            throw AuthException::UserNotFound('Wrong email or password!');
        }

        $this->ensurePasswordsSame(
          Password::fromString($command->password()),
          $user->getHashedPassword()
        );

        $user = $this->queryBus->dispatch(
          new GetUserByIdQuery($user->getId()),
          GetUserByIdHandler::class
        );

        return (new UserPresenter($user))->toBase();
    }

    public function ensurePasswordsSame(Password $password, HashedPassword $hashedPassword): void
    {
        if (! $this->passwordHasher->verify($password, $hashedPassword)){
            throw AuthException::UserNotFound('Wrong email or password!');
        }
    }

}