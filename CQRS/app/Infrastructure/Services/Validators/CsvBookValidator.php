<?php

namespace App\Infrastructure\Services\Validators;

use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Book\Service\CsvValidatorServiceInterface;

class CsvBookValidator implements CsvValidatorServiceInterface
{

    private array $allowedExtension = ['text/csv', 'application/csv'];

    /**
     * @throws \App\Domain\Based\Exception\InvalidFormat
     */
    public function isCsvFormat(array $fileInfo): void
    {
        $filesCount = count($fileInfo['name']);

        for ($i = 0; $i < $filesCount; ++$i) {
            if ( ! in_array($fileInfo['type'][$i], $this->allowedExtension,
              true)) {
                throw new InvalidFormat("File {$fileInfo['name'][$i]} 
                has invalid format: {$fileInfo['name'][$i]}. Allowed types: .csv");
            }
        }
    }

    /**
     * @throws \App\Domain\Based\Exception\InvalidFormat
     */
    public function ensureIsbnIsUniqueInEachFile(array $mergedBooks): void
    {
        $equalIsbn = $this->findEqualIsbn($mergedBooks);

        if ( ! $equalIsbn) {
            return;
        }

        $errorMessage = '';

        foreach ($equalIsbn as $fileName => $isbns){
            $errorMessage = "In {$fileName}: ISBN(s): ";

            foreach ($isbns as $isbnNo => $places){
                $placesToString = implode(', ', $places);
                $errorMessage .= "{$isbnNo} is same in {$placesToString} lines. \n";
            }
        }
        $errorMessage = rtrim($errorMessage, '\n');

        throw new InvalidFormat("Error: $errorMessage");
    }

    private function findEqualIsbn(array $mergedBooks): array
    {
        $seenISBNs = [];
        $duplicateIndexes = [];

        foreach ($mergedBooks as $fileName => $book) {
            foreach ($book as $index => $item) {
                $isbn = $item->isbn->value();

                if (isset($seenISBNs[$isbn])) {
                    if ( ! isset($duplicateIndexes[$fileName][$isbn])) {
                        $duplicateIndexes[$fileName][$isbn][] = $seenISBNs[$isbn];
                    }

                    $duplicateIndexes[$fileName][$isbn][] = $index + 1;
                } else {
                    $seenISBNs[$isbn] = $index + 1;
                }
            }
        }

        return $duplicateIndexes;
    }

}