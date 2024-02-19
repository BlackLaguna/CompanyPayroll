<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Exception;

use Exception;

final class DuplicateEntryInPayrollReport extends Exception
{
    public function __construct()
    {
        parent::__construct('Duplicate entry in payroll report');
    }
}