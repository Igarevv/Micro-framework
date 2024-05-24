<?php

namespace App\Presentation\Controllers;

use App\Application\UseCase\Book\GetBooksPaginated\GetPaginatedBooksQuery;
use App\Application\UseCase\Book\GetBooksPaginated\Home\GetFullBookDataQuery;
use App\Application\UseCase\Book\GetBooksPaginated\Home\GetFullBookDataQueryHandler;
use App\Application\UseCase\Book\GetBooksPaginated\Home\GetHomePageQueryHandler;
use App\Domain\Book\Enum\PagePaginator;
use App\Domain\Book\Exception\BookException;
use App\Infrastructure\Bus\Query\QueryBusInterface;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\Response;
use Throwable;

class HomeController extends Controller
{

    public function __construct(
      private readonly QueryBusInterface $bus,
    ) {}

    public function index(): Response
    {
        $page = $this->request->getGet(['page']);

        $page[PagePaginator::SHOW->value] = 10;

        try {
            $books = $this->bus->dispatch(new GetPaginatedBooksQuery($page),
              GetHomePageQueryHandler::class);
        } catch (Throwable $e) {
            return $this->render('home.html.twig');
        }

        return $this->render('home.html.twig', $books);
    }

    public function getOneBook(string $bookUrlId): Response
    {
        try {
            $book = $this->bus->dispatch(new GetFullBookDataQuery($bookUrlId),
            GetFullBookDataQueryHandler::class);
        } catch (BookException $e) {
            return $this->render('/static/404.twig',
              ['message' => $e->getMessage()]);
        }

        return $this->render('/book.page.twig', ['book' => $book]);
    }
}