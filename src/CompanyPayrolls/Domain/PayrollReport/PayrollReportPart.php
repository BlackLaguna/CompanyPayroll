<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain\PayrollReport;

use CompanyPayrolls\Domain\BonusType;
use CompanyPayrolls\Domain\DepartmentName;
use CompanyPayrolls\Domain\EmployeeFirstName;
use CompanyPayrolls\Domain\EmployeeLastName;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'payroll_report_parts')]
class PayrollReportPart implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $uuid;

    #[ORM\Embedded(class: EmployeeFirstName::class, columnPrefix: 'employee_')]
    private EmployeeFirstName $employeeFirstName;

    #[ORM\Embedded(class: EmployeeLastName::class, columnPrefix: 'employee_')]
    private EmployeeLastName $employeeLastName;

    #[ORM\ManyToOne(targetEntity: PayrollReport::class, inversedBy: 'parts')]
    #[ORM\JoinColumn(name: 'payroll_report_uuid', referencedColumnName: 'uuid')]
    private PayrollReport $payrollReport;

    #[ORM\Embedded(class: DepartmentName::class, columnPrefix: 'department_')]
    private DepartmentName $departmentName;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $baseSalaryAmount;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $additionalToBaseSalaryAmount;

    #[ORM\Column(type: 'enum_bonus_types', nullable: false)]
    private BonusType $bonusType;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $salaryWithBonus;

    public function __construct(
        EmployeeFirstName $employeeFirstName,
        EmployeeLastName $employeeLastName,
        BonusType $bonusType,
        DepartmentName $departmentName,
        int $baseSalaryAmount,
        int $additionalToBaseSalaryAmount
    ) {
        $this->employeeFirstName = $employeeFirstName;
        $this->employeeLastName = $employeeLastName;
        $this->bonusType = $bonusType;
        $this->departmentName = $departmentName;
        $this->baseSalaryAmount = $baseSalaryAmount;
        $this->additionalToBaseSalaryAmount = $additionalToBaseSalaryAmount;
        $this->salaryWithBonus = $baseSalaryAmount + $additionalToBaseSalaryAmount;
    }

    public function isSamePart(PayrollReportPart $part): bool
    {
        return $this->payrollReport->getDate() === $part->getPayrollReport()->getDate()
            && $this->employeeFirstName->equals($part->getEmployeeFirstName())
            && $this->employeeLastName->equals($part->getEmployLastName())
            && $this->departmentName->equals($part->getDepartmentName());
    }

    public function getDepartmentName(): DepartmentName
    {
        return $this->departmentName;
    }

    public function getEmployeeFirstName(): EmployeeFirstName
    {
        return $this->employeeFirstName;
    }

    public function getEmployLastName(): EmployeeLastName
    {
        return $this->employeeLastName;
    }

    public function assignToPayrollReport(PayrollReport $payrollReport): void
    {
        $this->payrollReport = $payrollReport;
    }

    public function getPayrollReport(): PayrollReport
    {
        return $this->payrollReport;
    }

    /**
     * @return array{
     *     date: string,
     *     employee_first_name: string,
     *     employee_last_name: string,
     *     department_name: string,
     *     base_salary_amount: int,
     *     additional_to_base_salary_amount: int,
     *     bonus_type: string,
     *     salary_with_bonus: int
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'date' => $this->payrollReport->getDate()->format('Y-m'),
            'employee_first_name' => $this->employeeFirstName->getValue(),
            'employee_last_name' => $this->employeeLastName->getValue(),
            'department_name' => $this->departmentName->getValue(),
            'base_salary_amount' => $this->baseSalaryAmount,
            'additional_to_base_salary_amount' => $this->additionalToBaseSalaryAmount,
            'bonus_type' => $this->bonusType->name,
            'salary_with_bonus' => $this->salaryWithBonus,
        ];
    }
}