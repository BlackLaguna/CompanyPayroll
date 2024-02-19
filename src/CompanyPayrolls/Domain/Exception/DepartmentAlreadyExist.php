<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Exception;

final class DepartmentAlreadyExist extends \Exception
{
    public function __construct()
    {
        parent::__construct('Department same name already exist');
    }
}