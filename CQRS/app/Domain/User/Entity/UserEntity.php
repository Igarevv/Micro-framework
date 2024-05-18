<?php

namespace App\Domain\User\Entity;

use App\Domain\User\ValueObject\HashedPassword;

class UserEntity
{

    private ?\DateTimeImmutable $createdAt = null;

    private HashedPassword $hashedPassword;

    public function __construct(
      private readonly int $id,
      private readonly string $firstName,
      private readonly string $lastName,
      private readonly string $email
    ) {
    }

    public static function create(
      int $id,
      string $firstName,
      string $lastName,
      string $email
    ): static {
        return new static($id, $firstName, $lastName, $email);
    }

    public function getCreatedAt(): ?\DateTimeImmutable
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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }

}