<?php

declare(strict_types=1);

namespace Tests\Fake\CompanyPayrolls;

use CompanyPayrolls\Domain\PayrollReport\PayrollReport;
use DateTime;
use Symfony\Component\Uid\Uuid;

final class PayrollReportMother
{
    public static function createValid(): PayrollReport
    {
        return new PayrollReport(
            Uuid::fromString('ff4da84c-fffb-4f82-9073-68ac8a1e63bf'),
            (new DateTime('2020-01-01'))->modify('last day of this month')
        );
    }
}