<?php

declare(strict_types=1);

namespace CompanyPayrolls\Application\Query;

use CompanyPayrolls\Application\Filter\PayrollReportFilters;
use CompanyPayrolls\Application\Pagination\PayrollReportSortField;
use CompanyPayrolls\Application\View\PayrollReportView;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;

interface GetPayrollReportQuery
{
    public function getPayrollReport(
        string $companyName,
        PayrollReportDate $reportDate,
        ?PayrollReportFilters $filters,
        ?PayrollReportSortField $sortField
    ): PayrollReportView;
}