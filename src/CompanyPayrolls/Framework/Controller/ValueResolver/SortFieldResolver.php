<?php

declare(strict_types=1);

namespace CompanyPayrolls\Framework\Controller\ValueResolver;

use CompanyPayrolls\Application\Pagination\PayrollReportSortField;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('sortField')]
final class SortFieldResolver implements ValueResolverInterface
{
    /** @return iterable<PayrollReportSortField> */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$request->query->has('sort') || $argument->getType() !== PayrollReportSortField::class) {
            return;
        }

        /** @phpstan-ignore-next-line */
        yield new PayrollReportSortField($request->query->get('sort'));
    }
}
