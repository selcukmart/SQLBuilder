<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Symfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SelcukMartSQLBuilderBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
