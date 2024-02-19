<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Salary
{
    #[ORM\Column(type: 'integer', nullable: false)]
    private int $amount;

    #[ORM\Column(type: 'enum_currencies', length: 3, nullable: false)]
    private Currency $currency;

    public function __construct(int $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}