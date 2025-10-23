<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Enums;

enum OrderDirection: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';
}
