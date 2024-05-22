<?php

namespace App\Application\UseCase\Book\SaveCSV;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Infrastructure\Services\BookCsvService;

class SaveCsvBookHandler implements CommandHandlerInterface
{

    public function __construct(
      private BookRepositoryInterface $bookRepository,
      private BookCsvService $bookCsvService
    ) {}

    /**
     * @param SaveCsvBookCommand $command
     * @throws \App\Domain\Based\Exception\InvalidFormat
     */
    public function handle(CommandInterface $command): void
    {
        try {
            $books = $this->bookCsvService->getBooksData($command->getFiles());
        } catch (InvalidFormat $e){
            throw $e;
        }
        dd($books);
    }

}