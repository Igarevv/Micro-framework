<?php

namespace App\Application\UseCase\Book\GetBooksPaginated;

use App\Application\Presenter\TableBookPresenter;
use App\Domain\Based\Bus\Query\QueryHandleInterface;
use App\Domain\Based\Bus\Query\QueryInterface;
use App\Domain\Book\Enum\PagePaginator;
use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Repository\BookRepositoryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GetTableBooksQueryHandler implements QueryHandleInterface
{

    private int $showNumber;

    private int $pageNumber;

    public function __construct(
      private readonly BookRepositoryInterface $repository
    ) {
        $this->showNumber = PagePaginator::DEFAULT_TABLE_SHOW_NUM->value;
        $this->pageNumber = PagePaginator::DEFAULT_PAGE_START_NUM->value;
    }

    /**
     * @var GetPaginatedBooksCommand $command
     * @throws \App\Domain\Book\Exception\BookException
     */
    public function handle(QueryInterface $command): array
    {
        $this->ensureParamsAreValid($command->getParams());

        $limit = ($this->pageNumber - 1) * $this->showNumber;

        $paginatedBooks = $this->repository->getPaginated($limit,
          $this->showNumber);

        if (! count($paginatedBooks->getIterator())) {
            throw BookException::booksNotFound();
        }

        return $this->makePresentation($paginatedBooks);
    }

    private function ensureParamsAreValid(?array $params): void
    {
        if ( ! $params) {
            return;
        }

        $showNumber = $params[PagePaginator::SHOW->value] ?? PagePaginator::DEFAULT_TABLE_SHOW_NUM->value;
        $pageNumber = $params[PagePaginator::PAGE->value] ?? PagePaginator::DEFAULT_PAGE_START_NUM->value;

        if (! is_numeric($showNumber) || $showNumber <= 0) {
            $showNumber = PagePaginator::DEFAULT_TABLE_SHOW_NUM->value;
        }
        if (! is_numeric($pageNumber) || $pageNumber <= 0){
            $pageNumber = PagePaginator::DEFAULT_PAGE_START_NUM->value;
        }

        $this->showNumber = (int) $showNumber;
        $this->pageNumber = (int) $pageNumber;
    }

    private function makePresentation(Paginator $paginatedBooks): array
    {
        $books = [];

        foreach ($paginatedBooks as $book) {
            $data = $book->getBookAuthors()->getValues()[0];

            $books[PagePaginator::COLLECTION->value][] = (new TableBookPresenter($data))->toBase();
        }

        $books[PagePaginator::PAGES_COUNT->value] = ceil($paginatedBooks->count() / $this->showNumber);
        $books[PagePaginator::PAGE->value] = $this->pageNumber;

        return $books;
    }

}