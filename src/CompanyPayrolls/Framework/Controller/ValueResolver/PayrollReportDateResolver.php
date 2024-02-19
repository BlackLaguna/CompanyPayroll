<?php

declare(strict_types=1);

namespace CompanyPayrolls\Framework\Controller\ValueResolver;

use CompanyPayrolls\Domain\PayrollReport\PayrollReportDate;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('payrollDate')]
final class PayrollReportDateResolver implements ValueResolverInterface
{
    /**
     * @throws Exception
     * @return iterable<PayrollReportDate>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @phpstan-ignore-next-line */
        if (empty($request->attributes->get('_route_params')['payrollDate'])
            || $argument->getType() !== PayrollReportDate::class
        ) {
            return;
        }

        /** @phpstan-ignore-next-line */
        yield new PayrollReportDate(new DateTime($request->attributes->get('_route_params')['payrollDate']));
    }
}
