<?php

declare(strict_types=1);

namespace CompanyPayrolls\Application\Pagination;

use InvalidArgumentException;

final class PayrollReportSortField
{
    private const array ALLOWED_FIELDS = [
        'payrollReportDate',
        'employeeFirstName',
        'employeeLastName',
        'departmentName',
        'baseSalaryAmount',
        'additionalToBaseSalaryAmount',
        'bonusType',
        'salaryWithBonus',
    ];

    public readonly string $direction;
    public readonly string $name;

    public function __construct(string $field)
    {
        if (str_starts_with($field, '-')) {
            $this->direction = 'DESC';
            $this->name = substr_replace($field, '', 0, 1);
        } else {
            $this->direction = 'ASC';
            $this->name = $field;
        }

        if (!in_array($this->name, self::ALLOWED_FIELDS, true)) {
            throw new InvalidArgumentException('Sort field is not allowed');
        }
    }
}
