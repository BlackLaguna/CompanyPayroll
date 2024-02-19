<?php

declare(strict_types=1);

namespace Tests\Fake\CompanyPayrolls;

use CompanyPayrolls\Domain\BonusType;
use CompanyPayrolls\Domain\Department;
use CompanyPayrolls\Domain\DepartmentName;
use CompanyPayrolls\Domain\SalaryBonus;

final class DepartmentMother
{
    public static function createTwoValid(): array
    {
        $hrDepartment = new Department(
            DepartmentName::createFromString('HR'),
            new SalaryBonus(BonusType::FIXED, 10000)
        );
        $crDepartment = new Department(
            DepartmentName::createFromString('CR'),
            new SalaryBonus(BonusType::PERCENTAGE, 10)
        );

        return [$hrDepartment, $crDepartment];
    }
}