<?php

namespace App\Application\Mappers;

use App\Domain\Based\ValueObject\Email;
use App\Domain\Based\ValueObject\FirstName;
use App\Domain\Based\ValueObject\LastName;
use App\Domain\User\Entity\UserEntity;
use App\Infrastructure\Persistence\Entity\User;

class UserMapper
{

    public function fromDomain(UserEntity $domainUser): User
    {
        $user = User::create(
          firstName: FirstName::fromString($domainUser->getFirstName()),
          lastName: LastName::fromString($domainUser->getLastName()),
          email: Email::fromString($domainUser->getEmail()),
          id: $domainUser->getId() ?: null
        );

        if ($domainUser->getHashedPassword()) {
            $user->setHashedPassword($domainUser->getHashedPassword());
        }

        return $user;
    }

    public function toDomain(User $dbUser): UserEntity
    {
        $user = new UserEntity(
          id: $dbUser->getId(),
          firstName: $dbUser->getFirstName()->value(),
          lastName: $dbUser->getLastName()->value(),
          email: $dbUser->getEmail()->value()
        );

        $user->setHashedPassword($dbUser->getHashedPassword());

        return $user;
    }

}