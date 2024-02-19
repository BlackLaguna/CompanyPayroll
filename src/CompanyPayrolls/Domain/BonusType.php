<?php

declare(strict_types=1);

namespace CompanyPayrolls\Domain;

enum BonusType
{
    case FIXED;
    case PERCENTAGE;
}