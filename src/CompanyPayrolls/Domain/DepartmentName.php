<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Embeddable]
class DepartmentName
{
    #[ORM\Column(type: 'string', length: 255)]
    public string $name;

    public function __construct(string $name)
    {
        if (strlen($name) > 255) {
            throw new InvalidArgumentException('First name cannot be longer than 255 characters');
        }

        $this->name = $name;
    }

    public static function createFromString(string $name): self
    {
        return new self($name);
    }

    public function getValue(): string
    {
        return $this->name;
    }

    public function equals(self $departmentName): bool
    {
        return $this->name === $departmentName->getValue();
    }
}