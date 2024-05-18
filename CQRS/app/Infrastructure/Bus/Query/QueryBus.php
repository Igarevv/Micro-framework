<?php

namespace App\Infrastructure\Bus\Query;

use App\Domain\Based\Bus\Query\QueryHandleInterface;
use App\Domain\Based\Bus\Query\QueryInterface;
use League\Container\Container;
use RuntimeException;

class QueryBus implements QueryBusInterface
{

    public function __construct(
      private readonly Container $container
    ) {}

    public function dispatch(QueryInterface $query, string $queryHandler): mixed
    {
        if (! is_subclass_of($queryHandler, QueryHandleInterface::class)){
            throw new RuntimeException('Not a query handler class');
        }

        /**@var QueryHandleInterface $handler*/
        $handler = $this->container->get($queryHandler);

        return $handler->handle($query);
    }

}