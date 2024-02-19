<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Exception;

use Exception;

final class PayrollReportAlreadyExistException extends Exception
{
    public function __construct()
    {
        parent::__construct('Payroll report already exists');
    }
}