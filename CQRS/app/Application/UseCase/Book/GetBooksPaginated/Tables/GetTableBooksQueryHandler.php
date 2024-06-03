<?php

namespace App\Application\UseCase\Book\GetBooksPaginated\Tables;

use App\Application\Presenter\TableBookPresenter;
use App\Application\Traits\PaginatorTrait;
use App\Application\UseCase\Book\GetBooksPaginated\GetPaginatedBooksQuery;
use App\Domain\Based\Bus\Query\QueryHandleInterface;
use App\Domain\Based\Bus\Query\QueryInterface;
use App\Domain\Book\Enum\PagePaginator;
use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Repository\BookManagementRepositoryInterface;
use App\Domain\Book\Service\PaginatorInterface;
use App\Infrastructure\Services\Paginator;

class GetTableBooksQueryHandler implements QueryHandleInterface
{

    use PaginatorTrait;

    private int $showNumber;

    private int $pageNumber;

    public function __construct(
      private readonly BookManagementRepositoryInterface $repository
    ) {}

    /**
     * @var GetPaginatedBooksQuery $command
     * @throws \App\Domain\Book\Exception\BookException
     */
    public function handle(QueryInterface $command): array
    {
        [$this->showNumber, $this->pageNumber,] = $this->getValidParams($command->getParams(),
          PagePaginator::DEFAULT_TABLE_SHOW_NUM->value,
          PagePaginator::DEFAULT_PAGE_START_NUM->value
        );

        $limit = $this->countLimit($this->showNumber, $this->pageNumber);

        $paginatedBooks = $this->repository->getPublishedBooksPaginated($limit,
          $this->showNumber);

        return $this->makePresentation($paginatedBooks);
    }

    private function makePresentation(PaginatorInterface $paginatedBooks): array
    {
        $books = [];

        $collection = $paginatedBooks->getArrayData();

        if (! $collection){
            throw BookException::booksNotFound();
        }

        foreach ($collection as $book) {
            $books[PagePaginator::COLLECTION->value][] = (new TableBookPresenter($book))->toBase();
        }

        $books[PagePaginator::PAGES_COUNT->value] = ceil($paginatedBooks->count() / $this->showNumber);
        $books[PagePaginator::PAGE->value] = $this->pageNumber;
        $books[PagePaginator::SHOW->value] = $this->showNumber;

        return $books;
    }

}