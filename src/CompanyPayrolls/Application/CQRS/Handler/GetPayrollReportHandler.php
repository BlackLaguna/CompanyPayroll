<?php

declare(strict_types=1);

namespace CompanyPayrolls\Application\CQRS\Handler;

use CompanyPayrolls\Application\Query\GetPayrollReportQuery;
use CompanyPayrolls\Application\CQRS\Query\GetPayrollReport;
use CompanyPayrolls\Application\View\PayrollReportView;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetPayrollReportHandler
{
    public function __construct(private readonly GetPayrollReportQuery $getPayrollReportQuery)
    {
    }

    public function __invoke(GetPayrollReport $query): PayrollReportView
    {
        return $this->getPayrollReportQuery->getPayrollReport(
            $query->companyName,
            $query->reportDate,
            $query->filters,
            $query->sortField
        );
    }
}