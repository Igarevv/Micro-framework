<?php

namespace App\DTO;

class BookCollection
{
    public function __construct(
      public readonly Book $book,
      public readonly Author $author,
    ) {}

}