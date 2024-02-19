<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Service;

use CompanyPayrolls\Domain\BonusType;
use CompanyPayrolls\Domain\Employee;
use CompanyPayrolls\Domain\SalaryBonus;
use DateTime;
use Money\Currency;
use Money\Money;

final class CalculateAdditionalSalary
{
    private const int MAX_SENIORITY_YEARS = 10;
    private const int PERCENTAGE_FACTOR = 100;

    public function calculateAdditionalSalary(
        DateTime $reportDate,
        SalaryBonus $departmentSalaryBonus,
        Employee $employee
    ): int {
        return match ($departmentSalaryBonus->getType()) {
            BonusType::PERCENTAGE =>  $this->calculatePercentageBonus($employee, $departmentSalaryBonus),
            BonusType::FIXED => $this->calculateFixedBonus($reportDate, $employee, $departmentSalaryBonus),
        };
    }

    private function calculatePercentageBonus(Employee $employee, SalaryBonus $salaryBonus): int
    {
        $baseSalary = new Money(
            $employee->getSalary()->getAmount(),
            new Currency($employee->getSalary()->getCurrency()->name)
        );
        $salaryBonus = $baseSalary->multiply($salaryBonus->getIncreaseFactor() / self::PERCENTAGE_FACTOR);

        return (int) $salaryBonus->getAmount();
    }

    private function calculateFixedBonus(DateTime $date, Employee $employee, SalaryBonus $salaryBonus): int
    {
        if (($employeeSeniority = $employee->getSeniorityYears($date)) > self::MAX_SENIORITY_YEARS) {
            $employeeSeniority = self::MAX_SENIORITY_YEARS;
        }

        return $salaryBonus->getIncreaseFactor() * $employeeSeniority;
    }
}