<?php

declare(strict_types=1);

namespace Tests\Fake\CompanyPayrolls;

use CompanyPayrolls\Domain\Company;

final class CompanyMother
{
    public static function createValid(): Company
    {
        return new Company('XYZ');
    }
}