<?php

namespace App\Domain\Book\Service;

interface CsvValidatorServiceInterface
{
    public function isCsvFormat(array $fileInfo): void;

    public function ensureIsbnIsUniqueInEachFile(array $mergedBooks): void;
}