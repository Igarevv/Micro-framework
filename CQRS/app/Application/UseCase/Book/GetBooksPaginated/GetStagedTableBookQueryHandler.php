<?php

namespace App\Application\UseCase\Book\GetBooksPaginated;

use App\Application\Presenter\TableBookPresenter;
use App\Application\Traits\PaginatorTrait;
use App\Domain\Based\Bus\Query\QueryHandleInterface;
use App\Domain\Based\Bus\Query\QueryInterface;
use App\Domain\Book\Enum\PagePaginator;
use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Infrastructure\Services\Paginator;

class GetStagedTableBookQueryHandler implements QueryHandleInterface
{

    use PaginatorTrait;

    private string $showNumber;

    private string $pageNumber;

    public function __construct(
      private readonly BookRepositoryInterface $bookRepository
    ) {}

    public function handle(QueryInterface $command)
    {
        [$this->showNumber, $this->pageNumber] = $this->getValidParams($command->getParams(),
          PagePaginator::DEFAULT_TABLE_SHOW_NUM->value,
          PagePaginator::DEFAULT_PAGE_START_NUM->value
        );

        $limit = $this->countLimit($this->showNumber, $this->pageNumber);

        $stagedBooks = $this->bookRepository->getStagedBooksPaginated($limit, $this->showNumber);

        return $this->makePresentation($stagedBooks);
    }

    private function makePresentation(Paginator $paginatedBooks): array
    {
        $books = [];

        $collection = $paginatedBooks->getArrayData();

        foreach ($collection as $book) {
            $data = $book->getBookAuthors()->getValues()[0];

            $books[PagePaginator::COLLECTION->value][] = (new TableBookPresenter($data))->toBase();
        }

        $books[PagePaginator::PAGES_COUNT->value] = ceil($paginatedBooks->count() / $this->showNumber);
        $books[PagePaginator::PAGE->value] = $this->pageNumber;

        return $books;
    }

}