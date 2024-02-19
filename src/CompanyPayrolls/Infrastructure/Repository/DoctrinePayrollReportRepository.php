<?php

declare(strict_types=1);

namespace CompanyPayrolls\Infrastructure\Repository;

use CompanyPayrolls\Domain\PayrollReport\PayrollReport;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;
use CompanyPayrolls\Domain\Repository\PayrollReportRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrinePayrollReportRepository implements PayrollReportRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function findOneBy(string $companyName, PayrollReportDate $reportDate): ?PayrollReport
    {
        return $this->entityManager->getRepository(PayrollReport::class)->findOneBy(
            [
                'company' => $companyName,
                'reportDate.date' => $reportDate->getDate(),
            ]
        );
    }

    public function persist(PayrollReport $payrollReport): void
    {
        $this->entityManager->persist($payrollReport);
    }
}