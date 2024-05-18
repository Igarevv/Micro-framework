<?php

namespace App\Infrastructure\Persistence\Types;

use App\Domain\Book\ValueObject\Year;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class YearType extends Type
{

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Year
    {
        return Year::fromStringToInt($value);
    }

}