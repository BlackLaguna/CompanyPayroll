<?php

declare(strict_types=1);

namespace CompanyPayrolls\Framework\Controller;

use CompanyPayrolls\Application\CQRS\Command\GeneratePayrollReportForCurrentMonthCommand;
use CompanyPayrolls\Application\CQRS\Query\GetPayrollReport;
use CompanyPayrolls\Application\Filter\PayrollReportFilters;
use CompanyPayrolls\Application\Pagination\PayrollReportSortField;
use CompanyPayrolls\Domain\Exception\CompanyNotFoundException;
use CompanyPayrolls\Domain\Exception\PayrollReportAlreadyExistException;
use CompanyPayrolls\Domain\Exception\ReportNotFoundException;
use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;
use SharedKernel\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/companies/{companyName}/payrolls')]
class PayrollController extends AbstractController
{
    #[Route(path: '/generate_for_current_month', name: 'GENERATE_PAYROLL_FOR_CURRENT_MONTH', methods: ['POST'])]
    public function generateForCurrentMonth(string $companyName, MessageBusInterface $commandBus): JsonResponse
    {
        try {
            $commandBus->dispatch(new GeneratePayrollReportForCurrentMonthCommand($companyName));

            return new JsonResponse([
                'success' => true,
            ], Response::HTTP_CREATED);
        } catch (CompanyNotFoundException) {
            return new JsonResponse([
                'success' => false,
            ], Response::HTTP_NOT_FOUND);
        } catch (PayrollReportAlreadyExistException) {
            return new JsonResponse([
                'success' => false,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(
        path: '/{payrollDate}',
        name: 'GET_COMPANY_PAYROLL',
        requirements: [
            'payrollDate' => '\d{4}-\d{2}-\d{2}',
        ],
        methods: ['GET']
    )]
    public function getPayroll(
        string $companyName,
        #[ValueResolver('payrollDate')] PayrollReportDate $payrollDate,
        QueryBus $queryBus,
        #[ValueResolver('filters')] ?PayrollReportFilters $filters = null,
        #[ValueResolver('sortField')] ?PayrollReportSortField $sortField = null,
    ): JsonResponse {
        try {
            $report = $queryBus->dispatch(new GetPayrollReport($companyName, $payrollDate, $filters, $sortField));

            return new JsonResponse($report, Response::HTTP_OK);
        } catch (CompanyNotFoundException|ReportNotFoundException) {
            return new JsonResponse([
                'success' => false,
            ], Response::HTTP_NOT_FOUND);
        }
    }
}