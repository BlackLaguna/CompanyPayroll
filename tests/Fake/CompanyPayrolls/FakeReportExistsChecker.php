<?php

declare(strict_types=1);

namespace Tests\Fake\CompanyPayrolls;

use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;
use CompanyPayrolls\Domain\Service\ReportExistsChecker;

final class FakeReportExistsChecker implements ReportExistsChecker
{
    public function isReportExists(string $companyName, PayrollReportDate $reportDate): bool
    {
        return false;
    }
}