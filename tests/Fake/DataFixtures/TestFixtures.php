<?php

declare(strict_types=1);

namespace Tests\Fake\DataFixtures;

use CompanyPayrolls\Domain\BonusType;
use CompanyPayrolls\Domain\Department;
use CompanyPayrolls\Domain\Employee;
use CompanyPayrolls\Domain\Exception\DepartmentAlreadyExist;
use CompanyPayrolls\Domain\Exception\EmployeeAlreadyExistInDepartment;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportPart;
use Tests\Fake\CompanyPayrolls\CompanyMother;
use Tests\Fake\CompanyPayrolls\DepartmentMother;
use Tests\Fake\CompanyPayrolls\EmployeeMother;
use Tests\Fake\CompanyPayrolls\PayrollReportMother;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class TestFixtures extends Fixture
{
    /**
     * @throws DepartmentAlreadyExist
     * @throws EmployeeAlreadyExistInDepartment
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
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


        $payrollReport = PayrollReportMother::createValid();
        $payrollReport->assignToCompany($company);

        $payrollReportPart1 = new PayrollReportPart(
            $employee1->getFirstName(),
            $employee1->getLastName(),
            BonusType::FIXED,
            $hrDepartment->getName(),
            $employee1->getSalary()->getAmount(),
            100000,
        );
        $payrollReportPart2 = new PayrollReportPart(
            $employee2->getFirstName(),
            $employee2->getLastName(),
            BonusType::PERCENTAGE,
            $crDepartment->getName(),
            $employee2->getSalary()->getAmount(),
            11000,
        );

        $payrollReport->addReportPart($payrollReportPart1);
        $payrollReport->addReportPart($payrollReportPart2);

        $manager->persist($company);
        $manager->persist($payrollReport);
        $manager->flush();
    }
}
