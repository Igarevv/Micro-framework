<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Based\ValueObject\Email;
use App\Domain\Based\ValueObject\FirstName;
use App\Domain\Based\ValueObject\LastName;
use App\Domain\User\ValueObject\HashedPassword;
use App\Infrastructure\Services\EntityManager\Contracts\DatabaseEntity;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('book_user')]
class User implements DatabaseEntity
{
    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $createdAt;

    #[Column(name: 'password', type: 'HashedPassword')]
    private HashedPassword $hashedPassword;

    public function __construct(
      #[Column(name: 'first_name', type: 'FirstName')]
      private readonly FirstName $firstName,

      #[Column(name: 'last_name', type: 'LastName')]
      private readonly LastName $lastName,

      #[Column(type: 'Email')]
      private readonly Email $email,

      #[Id, Column(type: Types::STRING, options: ['unsigned']), GeneratedValue]
      private readonly ?int $id = null,
    ) {
        $this->createdAt = new DateTimeImmutable('now');
    }

    public static function create(
      FirstName $firstName,
      LastName $lastName,
      Email $email,
      ?int $id = null
    ): static {
        return new static($firstName, $lastName, $email, $id);
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getHashedPassword(): HashedPassword
    {
        return $this->hashedPassword;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }

}