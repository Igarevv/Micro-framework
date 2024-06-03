<?php

namespace App\Application\UseCase\Book\GetBooksPaginated\Home;

use App\Application\Presenter\HomePagePresenter;
use App\Application\Traits\PaginatorTrait;
use App\Application\UseCase\Book\GetBooksPaginated\GetPaginatedBooksQuery;
use App\Domain\Based\Bus\Query\QueryHandleInterface;
use App\Domain\Based\Bus\Query\QueryInterface;
use App\Domain\Book\Enum\PagePaginator;
use App\Domain\Book\Repository\ImageRepositoryInterface;
use App\Domain\Book\Repository\PublicBookRepositoryInterface;
use App\Domain\Book\Service\PaginatorInterface;
use App\Infrastructure\Services\Paginator;

class GetHomePageQueryHandler implements QueryHandleInterface
{

    use PaginatorTrait;

    private int $showNumber;

    private int $pageNumber;

    public function __construct(
      private readonly PublicBookRepositoryInterface $bookRepository,
      private readonly ImageRepositoryInterface $imageRepository
    ) {}

    /**
     * @param  GetPaginatedBooksQuery  $command
     *
     * @throws \Exception
     */
    public function handle(QueryInterface $command): array
    {
        [$this->showNumber, $this->pageNumber] = $this->getValidParams($command->getParams(),
          PagePaginator::DEFAULT_HOME_PAGE_SHOW_NUM->value,
          PagePaginator::DEFAULT_PAGE_START_NUM->value
        );

        $limit = $this->countLimit($this->showNumber, $this->pageNumber);

        $paginatedBooks = $this->bookRepository->getPreviewBooks($limit,
          $this->showNumber);

        return $this->makePresentation($paginatedBooks);
    }

    private function makePresentation(PaginatorInterface $paginatedBooks): array
    {
        $books = [];

        $collection = $paginatedBooks->getArrayData();

        $imageUrls = $this->getImageUrls($collection);

        foreach ($collection as $key => $book) {
            $books[PagePaginator::COLLECTION->value][] = (new HomePagePresenter($book,
              $imageUrls[$key]['url']))->toBase();
        }

        $books[PagePaginator::PAGES_COUNT->value] = ceil($paginatedBooks->count() / $this->showNumber);
        $books[PagePaginator::PAGE->value] = $this->pageNumber;

        return $books;
    }

    private function getImageUrls(array $paginatedBooks): array
    {
        $imageUrls = [];

        foreach ($paginatedBooks as $book) {
            $imageUrls[] = $book->getImageId();
        }

        return $this->imageRepository->getImagesUrl($imageUrls);
    }

}