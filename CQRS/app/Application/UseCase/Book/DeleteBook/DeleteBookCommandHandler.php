<?php

namespace App\Application\UseCase\Book\DeleteBook;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Book\Repository\BookRepositoryInterface;

class DeleteBookCommandHandler implements CommandHandlerInterface
{

    public function __construct(
      private readonly BookRepositoryInterface $repository
    ) {}

    /**@var DeleteBookCommand $command*/
    public function handle(CommandInterface $command): void
    {
        $this->repository->delete($command->getId());
    }

}