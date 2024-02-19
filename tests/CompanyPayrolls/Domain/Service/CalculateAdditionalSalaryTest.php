<?php

declare(strict_types=1);

namespace Tests\CompanyPayrolls\Domain\Service;

use CompanyPayrolls\Domain\BonusType;
use CompanyPayrolls\Domain\Currency;
use CompanyPayrolls\Domain\Employee;
use CompanyPayrolls\Domain\EmployeeFirstName;
use CompanyPayrolls\Domain\EmployeeLastName;
use CompanyPayrolls\Domain\Salary;
use CompanyPayrolls\Domain\SalaryBonus;
use CompanyPayrolls\Domain\Service\CalculateAdditionalSalary;
use DateTime;
use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CalculateAdditionalSalaryTest extends TestCase
{
    /** @dataProvider fixedBonusDataProvider */
    public function testItCorrectCalculateFixedBonusAmount(
        int $salary,
        DateTime $employerStartDay,
        int $salaryIncreaseFactor,
        int $expectedBonus
    ): void {
        $reportDate = new DateTime();
        $employer = new Employee(
            Uuid::v4(),
            EmployeeFirstName::createFromString('firstName'),
            EmployeeLastName::createFromString('lastName'),
            new Salary($salary, Currency::USD),
            $employerStartDay
        );
        $salaryBonus = new SalaryBonus(BonusType::FIXED, $salaryIncreaseFactor);
        $calculator = new CalculateAdditionalSalary();

        Assert::assertEquals(
            $expectedBonus,
            $calculator->calculateAdditionalSalary($reportDate, $salaryBonus, $employer)
        );
    }

    /** @dataProvider percentageBonusDataProvider */
    public function testItCorrectCalculatePercentageBonusAmount(
        int $salary,
        DateTime $employerStartDay,
        int $salaryIncreaseFactor,
        int $expectedBonus
    ): void {
        $reportDate = new DateTime();
        $employer = new Employee(
            Uuid::v4(),
            EmployeeFirstName::createFromString('firstName'),
            EmployeeLastName::createFromString('lastName'),
            new Salary($salary, Currency::USD), $employerStartDay
        );
        $salaryBonus = new SalaryBonus(BonusType::PERCENTAGE, $salaryIncreaseFactor);
        $calculator = new CalculateAdditionalSalary();

        Assert::assertEquals(
            $expectedBonus,
            $calculator->calculateAdditionalSalary($reportDate, $salaryBonus, $employer)
        );
    }

    public function fixedBonusDataProvider(): Generator
    {
        yield  [100000, new DateTime('2010-01-01'), 10000, 100000];
        yield  [100000, new DateTime('2020-01-01'), 10000, 40000];
        yield  [100000, new DateTime('2024-01-01'), 10000, 0];
    }

    public function percentageBonusDataProvider(): Generator
    {
        yield  [110000, new DateTime('2010-01-01'), 10, 11000];
        yield  [110000, new DateTime('2020-01-01'), 10, 11000];
        yield  [110000, new DateTime('2024-01-01'), 10, 11000];
    }
}