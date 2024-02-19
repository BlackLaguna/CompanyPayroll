<?php

declare(strict_types=1);

namespace CompanyPayrolls\Application\Exception;

use Exception;

final class InvalidFiltersException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid filters');
    }
}