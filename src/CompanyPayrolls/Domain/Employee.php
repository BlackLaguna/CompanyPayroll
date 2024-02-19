<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'employees')]
class Employee
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $uuid;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'employees')]
    #[ORM\JoinColumn(name: 'department_uuid', referencedColumnName: 'uuid')]
    private Department $department;

    #[ORM\Embedded(class: EmployeeFirstName::class, columnPrefix: false)]
    private EmployeeFirstName $firstName;

    #[ORM\Embedded(class: EmployeeLastName::class, columnPrefix: false)]
    private EmployeeLastName $lastName;

    #[ORM\Embedded(class: Salary::class, columnPrefix: false)]
    private Salary $salary;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private DateTime $startDate;

    public function __construct(
        Uuid $uuid,
        EmployeeFirstName $firstName,
        EmployeeLastName $lastName,
        Salary $salary,
        DateTime $startDate
    ) {
        $this->uuid = $uuid;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->salary = $salary;
        $this->startDate = $startDate;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function assignToDepartment(Department $department): void
    {
        $this->department = $department;
    }

    public function getFirstName(): EmployeeFirstName
    {
        return $this->firstName;
    }

    public function getLastName(): EmployeeLastName
    {
        return $this->lastName;
    }

    public function getSalary(): Salary
    {
        return $this->salary;
    }

    public function getSeniorityYears(DateTime $date): int
    {
        return $date->diff($this->startDate)->y;
    }
}