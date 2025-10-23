<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder;

use SelcukMart\SQLBuilder\Builder\QueryBuilder;
use SelcukMart\SQLBuilder\Builder\InsertBuilder;
use SelcukMart\SQLBuilder\Builder\UpdateBuilder;
use SelcukMart\SQLBuilder\Builder\DeleteBuilder;
use SelcukMart\SQLBuilder\Builder\ReplaceBuilder;
use SelcukMart\SQLBuilder\Builder\CreateTableBuilder;
use SelcukMart\SQLBuilder\Builder\CreateIndexBuilder;
use SelcukMart\SQLBuilder\Builder\DropBuilder;
use SelcukMart\SQLBuilder\Builder\TruncateBuilder;
use SelcukMart\SQLBuilder\Builder\ShowBuilder;
use SelcukMart\SQLBuilder\Builder\SetBuilder;
use SelcukMart\SQLBuilder\Builder\ExplainBuilder;
use SelcukMart\SQLBuilder\Builder\DescribeBuilder;
use SelcukMart\SQLBuilder\Builder\RenameBuilder;

class SQLBuilder
{
    // ==================== DML Operations ====================

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
     * Create a new REPLACE query builder
     */
    public static function replace(): ReplaceBuilder
    {
        return ReplaceBuilder::create();
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
     * Create a new TRUNCATE query builder
     */
    public static function truncate(string $table): TruncateBuilder
    {
        return TruncateBuilder::create()->table($table);
    }

    /**
     * Create a new query builder instance
     */
    public static function query(): QueryBuilder
    {
        return QueryBuilder::create();
    }

    // ==================== DDL Operations ====================

    /**
     * Create a new CREATE TABLE builder
     */
    public static function createTable(string $table): CreateTableBuilder
    {
        return CreateTableBuilder::create()->table($table);
    }

    /**
     * Create a new CREATE INDEX builder
     */
    public static function createIndex(string $name): CreateIndexBuilder
    {
        return CreateIndexBuilder::create()->name($name);
    }

    /**
     * Create a new DROP builder
     */
    public static function drop(): DropBuilder
    {
        return DropBuilder::create();
    }

    /**
     * Drop a table
     */
    public static function dropTable(string $table): DropBuilder
    {
        return DropBuilder::create()->table($table);
    }

    /**
     * Drop an index
     */
    public static function dropIndex(string $index, string $table): DropBuilder
    {
        return DropBuilder::create()->index($index, $table);
    }

    /**
     * Drop a database
     */
    public static function dropDatabase(string $database): DropBuilder
    {
        return DropBuilder::create()->database($database);
    }

    /**
     * Create a new RENAME TABLE builder
     */
    public static function rename(string $oldName, string $newName): RenameBuilder
    {
        return RenameBuilder::create()->table($oldName, $newName);
    }

    // ==================== Utility Operations ====================

    /**
     * Create a new SHOW builder
     */
    public static function show(): ShowBuilder
    {
        return ShowBuilder::create();
    }

    /**
     * Show tables
     */
    public static function showTables(?string $database = null): ShowBuilder
    {
        return ShowBuilder::create()->tables($database);
    }

    /**
     * Show databases
     */
    public static function showDatabases(): ShowBuilder
    {
        return ShowBuilder::create()->databases();
    }

    /**
     * Show columns from table
     */
    public static function showColumns(string $table): ShowBuilder
    {
        return ShowBuilder::create()->columns($table);
    }

    /**
     * Create a new SET builder
     */
    public static function set(array $variables = []): SetBuilder
    {
        $builder = SetBuilder::create();
        if (!empty($variables)) {
            $builder->variables($variables);
        }
        return $builder;
    }

    /**
     * Create a new EXPLAIN builder
     */
    public static function explain(QueryBuilder|string $query = null): ExplainBuilder
    {
        $builder = ExplainBuilder::create();
        if ($query instanceof QueryBuilder) {
            $builder->query($query);
        } elseif (is_string($query)) {
            $builder->rawQuery($query);
        }
        return $builder;
    }

    /**
     * Create a new DESCRIBE builder
     */
    public static function describe(string $table): DescribeBuilder
    {
        return DescribeBuilder::create()->table($table);
    }
}
