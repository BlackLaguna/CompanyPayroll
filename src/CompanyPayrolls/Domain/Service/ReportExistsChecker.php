<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Service;

use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;

interface ReportExistsChecker
{
    public function isReportExists(string $companyName, PayrollReportDate $reportDate): bool;
}