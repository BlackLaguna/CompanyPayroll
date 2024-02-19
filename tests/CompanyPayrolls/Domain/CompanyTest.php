<?php

declare(strict_types=1);

namespace Tests\CompanyPayrolls\Domain;

use CompanyPayrolls\Domain\Company;
use CompanyPayrolls\Domain\Department;
use CompanyPayrolls\Domain\Employee;
use CompanyPayrolls\Domain\Exception\DepartmentAlreadyExist;
use CompanyPayrolls\Domain\Exception\EmployeeAlreadyExistInDepartment;
use CompanyPayrolls\Domain\Service\CalculateAdditionalSalary;
use CompanyPayrolls\Domain\Service\PayrollReportGenerator;
use DateTime;
use PHPUnit\Framework\TestCase;
use Tests\Fake\CompanyPayrolls\CompanyMother;
use Tests\Fake\CompanyPayrolls\DepartmentMother;
use Tests\Fake\CompanyPayrolls\EmployeeMother;
use Tests\Fake\CompanyPayrolls\FakeReportExistsChecker;

final class CompanyTest extends TestCase
{
    public function testItCreateCorrectGeneratePayrollReport(): void
    {
        $generatePayrollService = new PayrollReportGenerator(new FakeReportExistsChecker());
        $calculateAdditionalSalary = new CalculateAdditionalSalary();

        $company = $this->createFilledCompany();
        $report = $company->generatePayrollReport(
            new DateTime('2024-02-01'),
            $generatePayrollService,
            $calculateAdditionalSalary
        );

        self::AssertEquals(
            [
                [
                    "date" => "2024-02",
                    "employee_first_name" => "Adam",
                    "employee_last_name" => "Kowalski",
                    "department_name" => "HR",
                    "base_salary_amount" => 100000,
                    "additional_to_base_salary_amount" => 100000,
                    "bonus_type" => "FIXED",
                    "salary_with_bonus" => 200000,
                ],
                [
                    "date" => "2024-02",
                    "employee_first_name" => "Ania",
                    "employee_last_name" => "Nowak",
                    "department_name" => "CR",
                    "base_salary_amount" => 110000,
                    "additional_to_base_salary_amount" => 11000,
                    "bonus_type" => "PERCENTAGE",
                    "salary_with_bonus" => 121000,
                ],
            ],
            $report->jsonSerialize()
        );
    }

    /**
     * @throws DepartmentAlreadyExist
     * @throws EmployeeAlreadyExistInDepartment
     */
    private function createFilledCompany(): Company
    {
        $company = CompanyMother::createValid();

        /**
         * @var Department $hrDepartment
         * @var Department $crDepartment
         */
        [$hrDepartment, $crDepartment] = DepartmentMother::createTwoValid();
        $hrDepartment->assignToCompany($company);
        $crDepartment->assignToCompany($company);

        /**
         * @var Employee $employee1
         * @var Employee $employee2
         */
        [$employee1, $employee2] = EmployeeMother::createTwoValid();
        $hrDepartment->addEmployer($employee1);
        $crDepartment->addEmployer($employee2);

        $company->addDepartment($hrDepartment);
        $company->addDepartment($crDepartment);

        return $company;
    }
}