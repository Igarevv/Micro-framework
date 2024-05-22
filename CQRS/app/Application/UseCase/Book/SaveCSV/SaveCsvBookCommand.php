<?php

namespace App\Application\UseCase\Book\SaveCSV;

use App\Domain\Based\Bus\Command\CommandInterface;

class SaveCsvBookCommand implements CommandInterface
{
    public function __construct(
      private array $filesData
    ) {}

    public function getFiles(): array
    {
        return $this->filesData;
    }

}