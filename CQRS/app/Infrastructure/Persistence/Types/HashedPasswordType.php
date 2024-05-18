<?php

namespace App\Infrastructure\Persistence\Types;

use App\Domain\User\ValueObject\HashedPassword;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class HashedPasswordType extends Type
{

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): HashedPassword
    {
        return HashedPassword::fromString($value);
    }

}