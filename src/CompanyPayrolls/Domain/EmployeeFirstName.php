<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Embeddable]
class EmployeeFirstName
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $firstName;

    public function __construct(string $firstName)
    {
        if (strlen($firstName) > 255) {
            throw new InvalidArgumentException('First name cannot be longer than 255 characters');
        }

        $this->firstName = $firstName;
    }

    public static function createFromString(string $firstName): self
    {
        return new self($firstName);
    }

    public function getValue(): string
    {
        return $this->firstName;
    }

    public function equals(self $firstName): bool
    {
        return $this->firstName === $firstName->getValue();
    }
}