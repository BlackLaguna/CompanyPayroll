<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Repository;

use CompanyPayrolls\Domain\Company;
use CompanyPayrolls\Domain\Exception\CompanyNotFoundException;

interface CompanyRepository
{
    /** @throws CompanyNotFoundException */
    public function getCompanyByName(string $companyId): Company;
}