<?php

declare(strict_types=1);

namespace Tests\Fake\CompanyPayrolls;

use CompanyPayrolls\Domain\Currency;
use CompanyPayrolls\Domain\Employee;
use CompanyPayrolls\Domain\EmployeeFirstName;
use CompanyPayrolls\Domain\EmployeeLastName;
use CompanyPayrolls\Domain\Salary;
use DateTime;
use Symfony\Component\Uid\Uuid;

final class EmployeeMother
{
    public static function createTwoValid(): array
    {
        $employee1 = new Employee(
            Uuid::fromString('64466ad6-9c9f-402e-a557-5033beb3678c'),
            EmployeeFirstName::createFromString('Adam'),
            EmployeeLastName::createFromString('Kowalski'),
            new Salary(100000, Currency::USD),
            new DateTime('2009-01-01')
        );

        $employee2 = new Employee(
            Uuid::fromString('ff4da84c-fffb-4f82-9073-68ac8a1e63bf'),
            EmployeeFirstName::createFromString('Ania'),
            EmployeeLastName::createFromString('Nowak'),
            new Salary(110000, Currency::USD),
            new DateTime('2019-01-01')
        );

        return [$employee1, $employee2];
    }
}