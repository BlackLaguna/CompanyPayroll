<?php

declare(strict_types=1);

namespace CompanyPayrolls\Infrastructure\Services;

use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;
use CompanyPayrolls\Domain\Repository\PayrollReportRepository;
use CompanyPayrolls\Domain\Service\ReportExistsChecker;

final class DoctrineReportExistsChecker implements ReportExistsChecker
{
    public function __construct(private readonly PayrollReportRepository $payrollReportRepository)
    {
    }
    public function isReportExists(string $companyName, PayrollReportDate $reportDate): bool
    {
        $result = $this->payrollReportRepository->findOneBy($companyName, $reportDate);

        return !empty($result);
    }
}