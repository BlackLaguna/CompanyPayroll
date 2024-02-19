<?php

declare(strict_types=1);

namespace CompanyPayrolls\Application\CQRS\Query;

use CompanyPayrolls\Application\Filter\PayrollReportFilters;
use CompanyPayrolls\Application\Pagination\PayrollReportSortField;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;

final readonly class GetPayrollReport
{
    public function __construct(
        public string $companyName,
        public PayrollReportDate $reportDate,
        public ?PayrollReportFilters $filters,
        public ?PayrollReportSortField $sortField
    ) {
    }
}