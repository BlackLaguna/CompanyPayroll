<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain;

use CompanyPayrolls\Domain\Exception\DepartmentAlreadyExist;
use CompanyPayrolls\Domain\PayrollReport\PayrollReport;
use CompanyPayrolls\Domain\Service\CalculateAdditionalSalary;
use CompanyPayrolls\Domain\Service\PayrollReportGenerator;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Entity]
#[ORM\Table(name: 'companies')]
class Company
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name;

    /** @var Collection<int, Department> */
    #[ORM\OneToMany(
        mappedBy: 'company',
        targetEntity: Department::class,
        cascade: ['PERSIST', 'REMOVE'],
        orphanRemoval: true
    )]
    private Collection $departments;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->departments = new ArrayCollection();
    }

    /** @throws DepartmentAlreadyExist */
    public function addDepartment(Department $department): void
    {
        if (!$this->departments->contains($department)) {
            $this->departments->add($department);
        } else {
            throw new DepartmentAlreadyExist();
        }
    }

    /** @return Collection<int, Department> */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @throws Exception */
    public function generatePayrollReport(
        DateTime $reportDate,
        PayrollReportGenerator $generatePayrollService,
        CalculateAdditionalSalary $payrollReportPartGenerator
    ): PayrollReport {
        return $generatePayrollService->generateRaport(
            $this,
            $reportDate,
            $payrollReportPartGenerator,
        );
    }
}