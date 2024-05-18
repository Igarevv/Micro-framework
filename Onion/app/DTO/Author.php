<?php

namespace App\DTO;

class Author
{

    public function __construct(
      private readonly string $name,
      private readonly string $surname,
      private readonly ?int $id = null
    ) {}

    public static function fromState(
      string $name,
      string $surname,
      ?int $id = null
    ): static {
        return new static($name, $surname, $id);
    }

    public function firstName(): string
    {
        return $this->name;
    }
    public function fullName(): string
    {
        return "{$this->name} {$this->surname}";
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function lastName(): string
    {
        return $this->surname;
    }

}