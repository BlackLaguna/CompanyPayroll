<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain;

use CompanyPayrolls\Domain\Exception\EmployeeAlreadyExistInDepartment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'departments')]
class Department
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $uuid;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'departments')]
    #[ORM\JoinColumn(name: 'company_name', referencedColumnName: 'name')]
    private Company $company;

    #[ORM\Embedded(class: DepartmentName::class, columnPrefix: false)]
    private DepartmentName $name;

    #[ORM\Embedded(class: SalaryBonus::class, columnPrefix: false)]
    private SalaryBonus $salaryBonus;

    /** @var Collection<int, Employee> $employees  */
    #[ORM\OneToMany(
        mappedBy: 'department',
        targetEntity: Employee::class,
        cascade: ['PERSIST', 'REMOVE'],
        orphanRemoval: true
    )]
    private Collection $employees;

    public function __construct(DepartmentName $name, SalaryBonus $salaryBonus)
    {
        $this->name = $name;
        $this->salaryBonus = $salaryBonus;
        $this->employees = new ArrayCollection();
    }

    public function assignToCompany(Company $company): void
    {
        $this->company = $company;
    }

    /** @throws EmployeeAlreadyExistInDepartment */
    public function addEmployer(Employee $employee): void
    {
        if (!$this->employees->contains($employee)) {
            $employee->assignToDepartment($this);
            $this->employees->add($employee);
        } else {
            throw new EmployeeAlreadyExistInDepartment();
        }
    }

    public function getName(): DepartmentName
    {
        return $this->name;
    }

    public function getSalaryBonus(): SalaryBonus
    {
        return $this->salaryBonus;
    }

    /** @return Collection<int, Employee> */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }
}