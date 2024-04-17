<?php

namespace App\Entities;

use App\DTO\Image;

class BookCollection
{
    public function __construct(
      public readonly Book $book,
      public readonly Author $author,
      public readonly Image $image
    ) {}

}