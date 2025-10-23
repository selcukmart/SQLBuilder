<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Enums;

enum JoinType: string
{
    case INNER = 'INNER JOIN';
    case LEFT = 'LEFT JOIN';
    case RIGHT = 'RIGHT JOIN';
    case CROSS = 'CROSS JOIN';
    case FULL_OUTER = 'FULL OUTER JOIN';
    case LEFT_OUTER = 'LEFT OUTER JOIN';
}
