<?php

declare(strict_types=1);

namespace CompanyPayrolls\Application\View;

use CompanyPayrolls\Domain\PayrollReport\PayrollReportPart;
use JsonSerializable;

final readonly class PayrollReportView implements JsonSerializable
{
    /** @param array<PayrollReportPart> $parts */
    public function __construct(private array $parts)
    {
    }

    /** @param array<PayrollReportPart> $data */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }

    /** @return array{report: array<int, PayrollReportPart>} */
    public function jsonSerialize(): array
    {
        $report = [];

        foreach ($this->parts as $part) {
            $report[] = $part;
        }

        return [
            'report' => $report,
        ];
    }
}