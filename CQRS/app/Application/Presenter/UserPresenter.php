<?php

namespace App\Application\Presenter;


use App\Domain\User\Entity\UserEntity;
use stdClass;

class UserPresenter implements Presenter
{
    public const FIRST_NAME = 'firstName';

    public const LAST_NAME  = 'lastName';

    public const USER_ID    = 'id';

    public function __construct(
      private readonly UserEntity $domain
    ) {}

    public function toBase(): stdClass
    {
        $user = new stdClass();

        $user->id = $this->domain->getId();
        $user->firstName = $this->domain->getFirstName();
        $user->lastName = $this->domain->getLastName();

        return $user;
    }

    public function toArray(): array
    {
        return [
          self::FIRST_NAME => $this->domain->getFirstName(),
          self::LAST_NAME  => $this->domain->getLastName(),
          self::USER_ID    => $this->domain->getId()
        ];
    }

}