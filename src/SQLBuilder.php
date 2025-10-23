<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder;

use SelcukMart\SQLBuilder\Builder\QueryBuilder;
use SelcukMart\SQLBuilder\Builder\InsertBuilder;
use SelcukMart\SQLBuilder\Builder\UpdateBuilder;
use SelcukMart\SQLBuilder\Builder\DeleteBuilder;

class SQLBuilder
{
    /**
     * Create a new SELECT query builder
     *
     * @param string ...$columns
     */
    public static function select(string ...$columns): QueryBuilder
    {
        return QueryBuilder::create()->select(...$columns);
    }

    /**
     * Create a new SELECT query builder with table
     */
    public static function table(string $table, ?string $alias = null): QueryBuilder
    {
        return QueryBuilder::create()->from($table, $alias);
    }

    /**
     * Create a new INSERT query builder
     */
    public static function insert(): InsertBuilder
    {
        return InsertBuilder::create();
    }

    /**
     * Create a new UPDATE query builder
     */
    public static function update(string $table): UpdateBuilder
    {
        return UpdateBuilder::create()->table($table);
    }

    /**
     * Create a new DELETE query builder
     */
    public static function delete(string $table): DeleteBuilder
    {
        return DeleteBuilder::create()->from($table);
    }

    /**
     * Create a new query builder instance
     */
    public static function query(): QueryBuilder
    {
        return QueryBuilder::create();
    }
}
