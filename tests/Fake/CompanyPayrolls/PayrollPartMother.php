<?php

declare(strict_types=1);

namespace Tests\Fake\CompanyPayrolls;

use CompanyPayrolls\Domain\BonusType;
use CompanyPayrolls\Domain\DepartmentName;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportPart;

final class PayrollPartMother
{
    public static function createTwoValid(): array
    {
        [$employee1, $employee2] = EmployeeMother::createTwoValid();

        $reportPart1 = new PayrollReportPart(
            $employee1->getFirstName(),
            $employee1->getLastName(),
            BonusType::PERCENTAGE,
            DepartmentName::createFromString('HR'),
            $employee1->getSalary()->getAmount(),
            (int) ($employee1->getSalary()->getAmount() * 0.1),
        );

        $reportPart2 = new PayrollReportPart(
            $employee2->getFirstName(),
            $employee2->getLastName(),
            BonusType::FIXED,
            DepartmentName::createFromString('CR'),
            $employee2->getSalary()->getAmount(),
            ($employee2->getSalary()->getAmount() + 200),
        );

        return [$reportPart1, $reportPart2];
    }
}