<?php

namespace App\Entities;

class Book
{

    public function __construct(
      private readonly string $title,
      private readonly int $year,
      private readonly array $genre,
      private readonly string $description,
      private readonly string $image,
      private readonly int $isbn,
      private readonly ?int $id = null
    ) {}

    public static function fromState(
      string $title,
      int $year,
      array|string $genre,
      string $description,
      string $image,
      int $isbn,
      ?int $id = null
    ):static {
        return new static($title, $year, $genre, $description, $image, $isbn, $id);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getGenre(): false|string
    {
        return json_encode($this->genre, JSON_THROW_ON_ERROR);
    }

    public function getIsbn(): int
    {
        return $this->isbn;
    }

}