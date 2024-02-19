<?php

declare(strict_types=1);

namespace CompanyPayrolls\Framework\Controller\ValueResolver;

use CompanyPayrolls\Application\Exception\InvalidFiltersException;
use CompanyPayrolls\Application\Filter\PayrollReportFilters;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('filters')]
final class PayrollFiltersResolver implements ValueResolverInterface
{
    /**
     * @return iterable<PayrollReportFilters>
     * @throws InvalidFiltersException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$request->query->has('filters') || $argument->getType() !== PayrollReportFilters::class) {
            return;
        }

        yield new PayrollReportFilters($request->query->all('filters'));
    }
}
