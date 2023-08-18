<?php

declare(strict_types=1);

namespace App\Enums;

enum VacancyType: string
{
    case CLT = 'clt';
    case PJ = 'pj';
    case FREELANCER = 'freelancer';
}