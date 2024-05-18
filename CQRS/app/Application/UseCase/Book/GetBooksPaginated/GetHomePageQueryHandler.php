<?php

namespace App\Application\UseCase\Book\GetBooksPaginated;

use App\Application\Presenter\HomePagePresenter;
use App\Domain\Based\Bus\Query\QueryHandleInterface;
use App\Domain\Based\Bus\Query\QueryInterface;
use App\Domain\Book\Enum\PagePaginator;
use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Domain\Book\Repository\ImageRepositoryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GetHomePageQueryHandler implements QueryHandleInterface
{

    private int $showNumber;

    private int $pageNumber;

    public function __construct(
      private readonly BookRepositoryInterface $bookRepository,
      private readonly ImageRepositoryInterface $imageRepository
    ) {
        $this->showNumber = PagePaginator::DEFAULT_HOME_PAGE_SHOW_NUM->value;
        $this->pageNumber = PagePaginator::DEFAULT_PAGE_START_NUM->value;
    }

    /**
     * @param  GetPaginatedBooksCommand  $command
     *
     * @throws \App\Domain\Book\Exception\BookException
     * @throws \Exception
     */
    public function handle(QueryInterface $command): array
    {
        $this->ensureParamsAreValid($command->getParams());

        $limit = ($this->pageNumber - 1) * $this->showNumber;

        $paginatedBooks = $this->bookRepository->getPaginated($limit,
          $this->showNumber);

        if ( ! $paginatedBooks->getIterator()) {
            throw BookException::booksNotFound();
        }
        return $this->makePresentation($paginatedBooks);
    }

    private function ensureParamsAreValid(?array $params): void
    {
        if (! $params) {
            return;
        }

        $showNumber = $params[PagePaginator::SHOW->value] ?? PagePaginator::DEFAULT_HOME_PAGE_SHOW_NUM->value;
        $pageNumber = $params[PagePaginator::PAGE->value] ?? PagePaginator::DEFAULT_PAGE_START_NUM->value;

        if ( ! is_numeric($showNumber) || ! is_numeric($pageNumber)) {
            $this->showNumber = PagePaginator::DEFAULT_HOME_PAGE_SHOW_NUM->value;
            $this->pageNumber = PagePaginator::DEFAULT_PAGE_START_NUM->value;

            return;
        }

        $this->showNumber = (int) $showNumber;
        $this->pageNumber = (int) $pageNumber;
    }

    private function makePresentation(Paginator $paginatedBooks): array
    {
        $books = [];

        $imageUrls = $this->getImageUrls($paginatedBooks);

        foreach ($paginatedBooks as $key => $book) {
            $bookAuthor = $book->getBookAuthors()->getValues()[0];

            $books[PagePaginator::COLLECTION->value][] = (new HomePagePresenter($bookAuthor,
              $imageUrls[$key]['url']))->toBase();
        }

        $books[PagePaginator::PAGES_COUNT->value] = ceil(count($paginatedBooks) / $this->showNumber);
        $books[PagePaginator::PAGE->value] = $this->pageNumber;

        return $books;
    }

    private function getImageUrls(Paginator $paginatedBooks): array
    {
        $imageUrls = [];

        foreach ($paginatedBooks as $book) {
            $imageUrls[] = $book->getImageId();
        }

        return $this->imageRepository->getImagesUrl($imageUrls);
    }

}