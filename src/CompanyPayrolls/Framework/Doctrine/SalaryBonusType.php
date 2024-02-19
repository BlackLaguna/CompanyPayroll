<?php

declare(strict_types=1);

namespace CompanyPayrolls\Framework\Doctrine;

use CompanyPayrolls\Domain\BonusType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class SalaryBonusType extends Type
{
    private const string NAME = 'enum_bonus_types';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        /** @var BonusType $value */
        return match($value) {
            BonusType::FIXED => BonusType::FIXED->name,
            BonusType::PERCENTAGE => BonusType::PERCENTAGE->name,
        };
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): BonusType
    {
        /** @var string $value */
        return match($value) {
            BonusType::FIXED->name => BonusType::FIXED,
            BonusType::PERCENTAGE->name => BonusType::PERCENTAGE,
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
