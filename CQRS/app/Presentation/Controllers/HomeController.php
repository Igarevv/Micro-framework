<?php

namespace App\Presentation\Controllers;

use App\Application\UseCase\Book\GetBooksPaginated\GetHomePageQueryHandler;
use App\Application\UseCase\Book\GetBooksPaginated\GetPaginatedBooksCommand;
use App\Domain\Book\Enum\PagePaginator;
use App\Infrastructure\Bus\Query\QueryBusInterface;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\Response;
use Throwable;

class HomeController extends Controller
{

    public function __construct(
      private readonly QueryBusInterface $bus
    ) {}

    public function index(): Response
    {
        $page = $this->request->getGet(['page']);

        $page[PagePaginator::SHOW->value] = 10;

        try {
            $books = $this->bus->dispatch(new GetPaginatedBooksCommand($page),
              GetHomePageQueryHandler::class);
        } catch (Throwable $e) {
            return $this->render('home.html.twig');
        }

        return $this->render('home.html.twig', $books);
    }

}