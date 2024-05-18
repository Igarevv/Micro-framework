<?php

namespace App\Domain\Based\Bus\Query;

interface QueryHandleInterface
{
    public function handle(QueryInterface $command);
}