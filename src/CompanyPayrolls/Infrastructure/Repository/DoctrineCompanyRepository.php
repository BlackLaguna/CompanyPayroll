<?php

declare(strict_types=1);

namespace CompanyPayrolls\Infrastructure\Repository;

use CompanyPayrolls\Domain\Company;
use CompanyPayrolls\Domain\Exception\CompanyNotFoundException;
use CompanyPayrolls\Domain\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineCompanyRepository implements CompanyRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getCompanyByName(string $companyId): Company
    {
        $company = $this->entityManager->find(Company::class, $companyId);

        if ($company === null) {
            throw new CompanyNotFoundException();
        }

        return $company;
    }
}