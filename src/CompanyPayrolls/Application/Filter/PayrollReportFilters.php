<?php

declare(strict_types=1);

namespace CompanyPayrolls\Application\Filter;

use CompanyPayrolls\Application\Exception\InvalidFiltersException;

final class PayrollReportFilters
{
    private const array ALLOWED_FIELDS = [
        'departmentName',
        'employeeFirstName',
        'employeeLastName',
    ];

    /** @var array{departmentName?: string, employeeFirstName?: string, employeeLastName?: string} $filters */
    public array $filters;

    /**
     * @param array{departmentName?: string, employeeFirstName?: string, employeeLastName?: string} $filters
     * @throws InvalidFiltersException
     */
    public function __construct(array $filters)
    {
        $this->validateFilters($filters);
        $this->filters = $filters;
    }


    /**
     * @param array{departmentName?: string, employeeFirstName?: string, employeeLastName?: string} $filters
     * @throws InvalidFiltersException
     */
    private function validateFilters(array $filters): void
    {
        if (empty($filters)) {
            return;
        }

        foreach ($filters as $field => $value) {
            if (!in_array($field, self::ALLOWED_FIELDS, true)) {
                throw new InvalidFiltersException();
            }
        }
    }
}
