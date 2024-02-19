<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Exception;

use Exception;

final class ReportNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Report not found');
    }
}