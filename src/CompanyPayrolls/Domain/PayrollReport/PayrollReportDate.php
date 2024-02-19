<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\PayrollReport;

use CompanyPayrolls\Domain\Exception\InvalidReportDateException;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Embeddable]
class PayrollReportDate
{
    #[ORM\Column(type: 'date', nullable: false)]
    private DateTime $date;

    public function __construct(DateTime $reportDate)
    {
        try {
            $this->date = $reportDate->modify('last day of this month');
        } catch (Exception) {
            new InvalidReportDateException();
        }
    }

    public static function fromDateTime(DateTime $reportDate): self
    {
        return new self($reportDate);
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }
}