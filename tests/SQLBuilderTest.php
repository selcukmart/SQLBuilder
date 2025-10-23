<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Tests;

use PHPUnit\Framework\TestCase;
use SelcukMart\SQLBuilder\SQLBuilder;
use SelcukMart\SQLBuilder\Builder\QueryBuilder;
use SelcukMart\SQLBuilder\Builder\InsertBuilder;
use SelcukMart\SQLBuilder\Builder\UpdateBuilder;
use SelcukMart\SQLBuilder\Builder\DeleteBuilder;

class SQLBuilderTest extends TestCase
{
    public function testSelectFactory(): void
    {
        $builder = SQLBuilder::select('id', 'name');

        $this->assertInstanceOf(QueryBuilder::class, $builder);
        $this->assertStringContainsString('SELECT id, name', $builder->getSQL());
    }

    public function testTableFactory(): void
    {
        $builder = SQLBuilder::table('users');

        $this->assertInstanceOf(QueryBuilder::class, $builder);
        $this->assertStringContainsString('FROM users', $builder->getSQL());
    }

    public function testInsertFactory(): void
    {
        $builder = SQLBuilder::insert();

        $this->assertInstanceOf(InsertBuilder::class, $builder);
    }

    public function testUpdateFactory(): void
    {
        $builder = SQLBuilder::update('users');

        $this->assertInstanceOf(UpdateBuilder::class, $builder);
        $this->assertStringContainsString('UPDATE users', $builder->getSQL());
    }

    public function testDeleteFactory(): void
    {
        $builder = SQLBuilder::delete('users');

        $this->assertInstanceOf(DeleteBuilder::class, $builder);
        $this->assertStringContainsString('DELETE FROM users', $builder->getSQL());
    }

    public function testQueryFactory(): void
    {
        $builder = SQLBuilder::query();

        $this->assertInstanceOf(QueryBuilder::class, $builder);
    }

    public function testFluentChainExample(): void
    {
        $query = SQLBuilder::table('users')
            ->select('id', 'name', 'email')
            ->where('status', '=', 'active')
            ->orderBy('created_at')
            ->limit(10)
            ->getSQL();

        $this->assertStringContainsString('SELECT id, name, email', $query);
        $this->assertStringContainsString('FROM users', $query);
        $this->assertStringContainsString('WHERE status', $query);
        $this->assertStringContainsString('LIMIT 10', $query);
    }
}
