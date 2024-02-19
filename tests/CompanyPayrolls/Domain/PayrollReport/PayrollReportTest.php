<?php

declare(strict_types=1);

namespace Tests\CompanyPayrolls\Domain\PayrollReport;

use CompanyPayrolls\Domain\Exception\DuplicateEntryInPayrollReport;
use Tests\Fake\CompanyPayrolls\PayrollPartMother;
use Tests\Fake\CompanyPayrolls\PayrollReportMother;
use PHPUnit\Framework\TestCase;

class PayrollReportTest extends TestCase
{
    /** @throws DuplicateEntryInPayrollReport */
    public function testItCorrectCheckExistingPart(): void
    {
        $payrollReport = PayrollReportMother::createValid();
        [$reportPart1] = PayrollPartMother::createTwoValid();

        $payrollReport->addReportPart($reportPart1);
        self::expectException(DuplicateEntryInPayrollReport::class);
        $payrollReport->addReportPart($reportPart1);
    }
}