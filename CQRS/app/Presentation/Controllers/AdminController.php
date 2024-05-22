<?php

namespace App\Presentation\Controllers;

use App\Application\UseCase\Book\GetBooksPaginated\GetPaginatedBooksCommand;
use App\Application\UseCase\Book\GetBooksPaginated\GetStagedTableBookQueryHandler;
use App\Application\UseCase\Book\GetBooksPaginated\GetTableBooksQueryHandler;
use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Book\Exception\BookException;
use App\Infrastructure\Bus\Query\QueryBusInterface;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\RedirectResponse;
use Igarevv\Micrame\Http\Response\Response;

class AdminController extends Controller
{
    public function __construct(
      private readonly QueryBusInterface $bus
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
              new GetPaginatedBooksCommand($getParams), GetStagedTableBookQueryHandler::class
            );
        } catch (BookException|InvalidFormat $e){
            $this->request->session()->setFlash('errorFromDb', [
              'error' => $e->getMessage(),
            ]);
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
            $books = $this->bus->dispatch(new GetPaginatedBooksCommand($getParams),
              GetTableBooksQueryHandler::class);

        } catch (BookException $e){
            $this->request->session()->setFlash('error', [
              'error' => $e->getMessage(),
            ]);
            return new RedirectResponse('/admin/book');
        }

        return $this->render('/admin/admin.list.twig', $books);
    }

}