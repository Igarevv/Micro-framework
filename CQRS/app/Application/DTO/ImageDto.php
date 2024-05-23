<?php

namespace App\Application\DTO;

readonly class ImageDto
{
    public function __construct(
      private string $name,
      private ?string $tmpPath = null,
      private ?string $type = null
    ) {}

    public function getFileName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->tmpPath;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFullName(): string
    {
        return $this->name.".".$this->type;
    }
}