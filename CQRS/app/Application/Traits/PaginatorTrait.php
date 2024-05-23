<?php

namespace App\Application\Traits;

use App\Domain\Book\Enum\PagePaginator;

trait PaginatorTrait
{
    public function getValidParams(?array $params, int $defaultShowNumber, int $defaultPageNumber): array
    {
        if (! $params) {
            return [$defaultShowNumber, $defaultPageNumber];
        }

        $showNumber = $params[PagePaginator::SHOW->value] ?? $defaultShowNumber;
        $pageNumber = $params[PagePaginator::PAGE->value] ?? $defaultPageNumber;

        if (! is_numeric($showNumber) || $showNumber <= 0 || ! is_numeric($pageNumber) || $pageNumber <= 0) {
            return [$defaultShowNumber, $defaultPageNumber];
        }

        return [(int)$showNumber, (int)$pageNumber];
    }

    public function countLimit(int $showNumber, int $pageNumber): int
    {
        return ($pageNumber - 1) * $showNumber;
    }

}