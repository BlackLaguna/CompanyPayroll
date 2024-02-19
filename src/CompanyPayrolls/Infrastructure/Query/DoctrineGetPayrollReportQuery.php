<?php

declare(strict_types=1);

namespace CompanyPayrolls\Infrastructure\Query;

use CompanyPayrolls\Application\Filter\PayrollReportFilters;
use CompanyPayrolls\Application\Pagination\PayrollReportSortField;
use CompanyPayrolls\Application\Query\GetPayrollReportQuery;
use CompanyPayrolls\Application\View\PayrollReportView;
use CompanyPayrolls\Domain\Exception\ReportNotFoundException;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportPart;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

final class DoctrineGetPayrollReportQuery implements GetPayrollReportQuery
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /** @throws ReportNotFoundException */
    public function getPayrollReport(
        string $companyName,
        PayrollReportDate $reportDate,
        ?PayrollReportFilters $filters,
        ?PayrollReportSortField $sortField
    ): PayrollReportView {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('parts')
            ->from(PayrollReportPart::class, 'parts')
            ->join('parts.payrollReport', 'p', 'WITH', 'p.uuid = parts.payrollReport')
            ->where('p.reportDate.date = :reportDate')
            ->andWhere('p.company = :companyName')
            ->setParameter(':companyName', $companyName)
            ->setParameter(':reportDate', $reportDate->getDate());

        if ($filters) {
            if (isset($filters->filters['departmentName'])) {
                $qb->andWhere('parts.departmentName.name = :departmentName')
                    ->setParameter(':departmentName', $filters->filters['departmentName']);
            }

            if (isset($filters->filters['employeeFirstName'])) {
                $qb->andWhere('parts.employeeFirstName.firstName = :employeeFirstName')
                    ->setParameter(':employeeFirstName', $filters->filters['employeeFirstName']);
            }

            if (isset($filters->filters['employeeLastName'])) {
                $qb->andWhere('parts.employeeLastName.lastName = :employeeLastName')
                    ->setParameter(':employeeLastName', $filters->filters['employeeLastName']);
            }
        }

        if ($sortField) {
            match($sortField->name) {
                'payrollReportDate' => $qb->orderBy('p.reportDate.date', $sortField->direction),
                'employeeFirstName' => $qb->orderBy('parts.employeeFirstName.firstName', $sortField->direction),
                'employeeLastName' => $qb->orderBy('parts.employeeLastName.lastName', $sortField->direction),
                'departmentName' => $qb->orderBy('parts.departmentName.name', $sortField->direction),
                'baseSalaryAmount' => $qb->orderBy('parts.baseSalaryAmount', $sortField->direction),
                'additionalToBaseSalaryAmount' => $qb->orderBy(
                    'parts.additionalToBaseSalaryAmount', $sortField->direction
                ),
                'bonusType' => $qb->orderBy('parts.bonusType', $sortField->direction),
                'salaryWithBonus' => $qb->orderBy('parts.salaryWithBonus', $sortField->direction),
                default => throw new InvalidArgumentException('Sort field is not allowed'),
            };
        }

        $result = $qb->getQuery()->execute();

        if (empty($result)) {
            throw new ReportNotFoundException();
        }

        /** @var array<PayrollReportPart> $result */
        return PayrollReportView::createFromArray($result);
    }
}