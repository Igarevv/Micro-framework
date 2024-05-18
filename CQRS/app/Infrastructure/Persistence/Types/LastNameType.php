<?php

namespace App\Infrastructure\Persistence\Types;

use App\Domain\Based\ValueObject\LastName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class LastNameType extends Type
{

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): LastName
    {
        return LastName::fromString($value);
    }

}