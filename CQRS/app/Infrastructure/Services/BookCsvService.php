<?php

namespace App\Infrastructure\Services;

use App\Application\DTO\UploadCsvBooksDto;
use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Based\ValueObject\FirstName;
use App\Domain\Based\ValueObject\LastName;
use App\Domain\Book\Service\CsvValidatorServiceInterface;
use App\Domain\Book\ValueObject\Isbn;
use App\Domain\Book\ValueObject\Title;
use App\Domain\Book\ValueObject\Year;

class BookCsvService
{

    public function __construct(
      private CsvValidatorServiceInterface $validatorService
    ) {}

    /**
     * @throws \App\Domain\Based\Exception\InvalidFormat
     */
    public function getBooksData(array $filesInfo): array
    {
        $this->validatorService->isCsvFormat($filesInfo);

        $books = [];
        $filesCount = count($filesInfo['name']);

        for($i = 0; $i < $filesCount; ++$i){
            $result = $this->getArrayFromFiles($filesInfo['tmp_name'][$i]);

            if (! $result){
                continue;
            }

            $books[$filesInfo['name'][$i]] = $result;
        }

        $this->validatorService->ensureIsbnIsUniqueInEachFile($books);

        return $books;
    }

    private function getArrayFromFiles(string $filePath): array
    {
        $file = fopen($filePath, 'rb');

        $data = [];

        $lineNum = 0;

        while (($rows = fgetcsv($file, separator: ';')) !== false) {
            $lineNum++;

            [
              $title, $firstName, $lastName, $year, $isbn, $desc, $genres,
            ] = $rows;

            $genreStr = preg_replace('/\s+/', '', $genres);

            $genre = explode(',', strtolower($genreStr));

            try {
                $data[] = new UploadCsvBooksDto(
                  title: Title::fromString($title),
                  firstName: FirstName::fromString($firstName),
                  lastName: LastName::fromString($lastName),
                  year: Year::fromString($year),
                  isbn: Isbn::fromString($isbn),
                  description: $desc,
                  genre: $genre
                );
            } catch (InvalidFormat $e){
                throw new InvalidFormat("Error uploading file: {$e->getMessage()} on line {$lineNum}.");
            } catch (\Throwable $e){
                throw new InvalidFormat("Error uploading file: please check your file for compliance.");
            }
        }

        if (! $data){
            throw new InvalidFormat("Error uploading file: file is empty.");
        }

        return $data;
    }

}