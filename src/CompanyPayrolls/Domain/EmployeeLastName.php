<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Embeddable]
class EmployeeLastName
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $lastName;

    public function __construct(string $lastName)
    {
        if (strlen($lastName) > 255) {
            throw new InvalidArgumentException('Last name cannot be longer than 255 characters');
        }

        $this->lastName = $lastName;
    }

    public static function createFromString(string $lastName): self
    {
        return new self($lastName);
    }

    public function getValue(): string
    {
        return $this->lastName;
    }

    public function equals(self $lastName): bool
    {
        return $this->lastName === $lastName->getValue();
    }
}