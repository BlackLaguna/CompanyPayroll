<?php

declare(strict_types=1);

namespace CompanyPayrolls\Application\CQRS\Handler;

use CompanyPayrolls\Application\CQRS\Command\GeneratePayrollReportForCurrentMonthCommand;
use CompanyPayrolls\Domain\Exception\CompanyNotFoundException;
use CompanyPayrolls\Domain\Repository\CompanyRepository;
use CompanyPayrolls\Domain\Repository\PayrollReportRepository;
use CompanyPayrolls\Domain\Service\CalculateAdditionalSalary;
use CompanyPayrolls\Domain\Service\PayrollReportGenerator;
use DateTime;
use Exception;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GeneratePayrollReportForCurrentMonthHandler
{
    public function __construct(
        private PayrollReportGenerator $payrollReportGenerator,
        private CalculateAdditionalSalary $payrollReportPartGenerator,
        private CompanyRepository $companyRepository,
        private PayrollReportRepository $payrollReportRepository
    ) {
    }

    /**
     * @throws CompanyNotFoundException
     * @throws Exception
     */
    public function __invoke(GeneratePayrollReportForCurrentMonthCommand $command): void
    {
        $company = $this->companyRepository->getCompanyByName($command->companyName);
        $payrollReport = $company->generatePayrollReport(
            new DateTime(),
            $this->payrollReportGenerator,
            $this->payrollReportPartGenerator
        );
        $this->payrollReportRepository->persist($payrollReport);
    }
}