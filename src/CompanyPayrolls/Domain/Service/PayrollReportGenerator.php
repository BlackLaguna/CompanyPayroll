<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\Service;

use CompanyPayrolls\Domain\Company;
use CompanyPayrolls\Domain\Department;
use CompanyPayrolls\Domain\Employee;
use CompanyPayrolls\Domain\Exception\PayrollReportAlreadyExistException;
use CompanyPayrolls\Domain\PayrollReport\PayrollReport;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportPart;
use DateTime;
use Exception;
use Symfony\Component\Uid\Uuid;

final class PayrollReportGenerator
{
    public function __construct(private readonly ReportExistsChecker $reportExistsChecker)
    {
    }

    /** @throws Exception */
    public function generateRaport(
        Company $company,
        DateTime $reportDate,
        CalculateAdditionalSalary $payrollReportPartGenerator
    ): PayrollReport {
        $payrollReport = new PayrollReport(Uuid::v4(), $reportDate);
        $payrollReport->assignToCompany($company);

        if ($this->reportExistsChecker->isReportExists(
            $company->getName(),
            PayrollReportDate::fromDateTime($reportDate)
        )) {
            throw new PayrollReportAlreadyExistException();
        }

        /** @var Department $department */
        foreach ($company->getDepartments() as $department) {
            /** @var Employee $employee */
            foreach ($department->getEmployees() as $employee) {
                $payrollReport->addReportPart(
                    new PayrollReportPart(
                        $employee->getFirstName(),
                        $employee->getLastName(),
                        $department->getSalaryBonus()->getType(),
                        $department->getName(),
                        $employee->getSalary()->getAmount(),
                        $payrollReportPartGenerator->calculateAdditionalSalary(
                            $reportDate,
                            $department->getSalaryBonus(),
                            $employee
                        )
                    )
                );
            }
        }

        return $payrollReport;
    }
}