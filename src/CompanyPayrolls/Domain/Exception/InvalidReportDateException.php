<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Exception;

use Exception;

class InvalidReportDateException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid report date');
    }
}