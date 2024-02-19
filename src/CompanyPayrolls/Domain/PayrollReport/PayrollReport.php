<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\PayrollReport;

use CompanyPayrolls\Domain\Company;
use CompanyPayrolls\Domain\Exception\DuplicateEntryInPayrollReport;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'payroll_reports')]
class PayrollReport implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $uuid;

    #[ORM\Embedded(class: PayrollReportDate::class, columnPrefix: false)]
    private PayrollReportDate $reportDate;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: 'company_name', referencedColumnName: 'name')]
    private Company $company;

    /** @var Collection<int, PayrollReportPart> */
    #[ORM\OneToMany(
        mappedBy: 'payrollReport',
        targetEntity: PayrollReportPart::class,
        cascade: ['PERSIST', 'REMOVE'],
        orphanRemoval: true
    )]
    private Collection $reportParts;

    public function __construct(Uuid $uuid, DateTime $reportDate)
    {
        $this->uuid = $uuid;
        $this->reportDate = PayrollReportDate::fromDateTime($reportDate);
        $this->reportParts = new ArrayCollection();
    }

    public function assignToCompany(Company $company): void
    {
        $this->company = $company;
    }

    public function getDate(): DateTime
    {
        return $this->reportDate->getDate();
    }

    /** @throws DuplicateEntryInPayrollReport */
    public function addReportPart(PayrollReportPart $payrollReportPart): void
    {
        $payrollReportPart->assignToPayrollReport($this);
        $isRecordExist = $this->reportParts->exists(
            static function (int $key, PayrollReportPart $part) use ($payrollReportPart) {
                return $part->isSamePart($payrollReportPart);
        });

        if (!$isRecordExist) {
            $this->reportParts->add($payrollReportPart);
        } else {
            throw new DuplicateEntryInPayrollReport();
        }
    }

    /** @return array<int, array{
     *     date: string,
     *     employee_first_name: string,
     *     employee_last_name: string,
     *     department_name: string,
     *     base_salary_amount: int,
     *     additional_to_base_salary_amount: int,
     *     bonus_type: string,
     *     salary_with_bonus: int
     * }>
     */
    public function jsonSerialize(): array
    {
        $parts = [];

        foreach ($this->reportParts as $part) {
            $parts[] = $part->jsonSerialize();
        }

        return $parts;
    }
}