<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use SelcukMart\SQLBuilder\Builder\QueryBuilder;
use SelcukMart\SQLBuilder\Builder\InsertBuilder;
use SelcukMart\SQLBuilder\Builder\UpdateBuilder;
use SelcukMart\SQLBuilder\Builder\DeleteBuilder;

/**
 * @method static QueryBuilder select(string ...$columns)
 * @method static QueryBuilder table(string $table, ?string $alias = null)
 * @method static InsertBuilder insert()
 * @method static UpdateBuilder update(string $table)
 * @method static DeleteBuilder delete(string $table)
 * @method static QueryBuilder query()
 *
 * @see \SelcukMart\SQLBuilder\SQLBuilder
 */
class SQLBuilder extends Facade
{
    /**
     * Get the registered name of the component
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sqlbuilder';
    }
}
