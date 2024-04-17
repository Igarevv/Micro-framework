<?php

namespace App\Repository\Interfaces;

use App\Entities\BookCollection;

interface BookInterface
{
    public function save(BookCollection $bookCollection): void;

    public function findAll();
}