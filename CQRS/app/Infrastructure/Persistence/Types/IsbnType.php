<?php

namespace App\Infrastructure\Persistence\Types;

use App\Domain\Book\ValueObject\Isbn;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class IsbnType extends Type
{

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Isbn
    {
        return Isbn::fromStringToInt($value);
    }

}