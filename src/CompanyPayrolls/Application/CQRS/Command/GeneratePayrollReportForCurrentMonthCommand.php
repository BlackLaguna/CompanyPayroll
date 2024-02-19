<?php

declare(strict_types=1);

namespace CompanyPayrolls\Application\CQRS\Command;

final readonly class GeneratePayrollReportForCurrentMonthCommand
{
    public function __construct(public string $companyName)
    {
    }
}