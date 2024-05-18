<?php

namespace App\DTO;

class Image
{

    public function __construct(
      private readonly string $name,
      private readonly ?string $tmpPath = null,
      private readonly ?string $type = null
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