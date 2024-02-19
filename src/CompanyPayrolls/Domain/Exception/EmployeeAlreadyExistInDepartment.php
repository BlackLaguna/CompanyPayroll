<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Exception;

use Exception;

final class EmployeeAlreadyExistInDepartment extends Exception
{
    public function __construct()
    {
        parent::__construct('Employee already exist in department');
    }
}