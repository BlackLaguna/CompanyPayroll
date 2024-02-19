<?php

declare(strict_types=1);

namespace CompanyPayrolls\Framework\Doctrine;

use CompanyPayrolls\Domain\Currency;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CurrencyType extends Type
{
    private const string NAME = 'enum_currencies';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        /** @var Currency $value */
        return match($value) {
            Currency::USD => Currency::USD->name,
        };
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        /** @var string $value */
        return match($value) {
            Currency::USD->name => Currency::USD,
        };
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
