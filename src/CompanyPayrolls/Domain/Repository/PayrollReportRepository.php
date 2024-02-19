<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Repository;

use CompanyPayrolls\Domain\PayrollReport\PayrollReport;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;

interface PayrollReportRepository
{
    public function findOneBy(string $companyName, PayrollReportDate $reportDate): ?PayrollReport;
    public function persist(PayrollReport $payrollReport): void;
}