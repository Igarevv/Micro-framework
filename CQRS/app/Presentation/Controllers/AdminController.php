<?php

namespace App\Presentation\Controllers;

use App\Application\UseCase\Book\GetBooksPaginated\GetPaginatedBooksQuery;
use App\Application\UseCase\Book\GetBooksPaginated\Tables\GetStagedTableBookQueryHandler;
use App\Application\UseCase\Book\GetBooksPaginated\Tables\GetTableBooksQueryHandler;
use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Book\Exception\BookException;
use App\Infrastructure\Bus\Query\QueryBusInterface;
use App\Infrastructure\Services\Session\FlashMessageHandler;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\RedirectResponse;
use Igarevv\Micrame\Http\Response\Response;

class AdminController extends Controller
{
    public function __construct(
      private readonly QueryBusInterface $bus,
      private readonly FlashMessageHandler $flasher
    ) {}

    public function index(): Response
    {
        return $this->render('/admin/admin.home.twig');
    }

    public function showUnreadyBooks(): Response
    {
        $getParams = $this->request->getGet(['page', 'show']);

        try {
            $books = $this->bus->dispatch(
              new GetPaginatedBooksQuery($getParams), GetStagedTableBookQueryHandler::class
            );
        } catch (BookException|InvalidFormat $e){
            $this->flasher->setError('errorFromDb', $e->getMessage());

            return $this->render('/admin/admin.unready.twig');
        }

        return $this->render('/admin/admin.unready.twig', $books);
    }

    public function showBookForm(): Response
    {
        return $this->render('/admin/admin.addbook.twig');
    }

    public function showTable(): Response
    {
        $getParams = $this->request->getGet(['page', 'show']) ?: null;

        try {
            $books = $this->bus->dispatch(new GetPaginatedBooksQuery($getParams),
              GetTableBooksQueryHandler::class);

        } catch (BookException $e){
            $this->flasher->setError('error', $e->getMessage());

            return new RedirectResponse('/admin/book');
        }

        return $this->render('/admin/admin.list.twig', $books);
    }

}