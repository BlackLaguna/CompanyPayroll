<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SalaryBonus
{
    #[ORM\Column(type: 'enum_bonus_types', nullable: false)]
    private BonusType $type;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $increaseFactor;

    public function __construct(BonusType $type, int $salaryIncreaseFactor)
    {
        $this->type = $type;
        $this->increaseFactor = $salaryIncreaseFactor;
    }

    public function getType(): BonusType
    {
        return $this->type;
    }

    public function getIncreaseFactor(): int
    {
        return $this->increaseFactor;
    }
}