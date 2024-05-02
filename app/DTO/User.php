<?php

namespace App\DTO;

class User
{

    public function __construct(
      private ?int $id,
      private readonly string $firstName,
      private readonly string $lastName,
      private readonly string $email,
      private readonly ?string $password,
      private readonly ?\DateTimeImmutable $createdAt
    ) {}

    public static function make(
      string $firstName,
      string $lastName,
      string $email,
      string $password = null,
      int $id = null,
      \DateTimeImmutable $createdAt = null
    ): static {
        return new static($id, $firstName, $lastName, $email, $password, $createdAt);
    }

    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function password(): string
    {
        return $this->password;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setUserId(int $id): void
    {
        $this->id = $id;
    }

}