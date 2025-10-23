<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Tests\Builder;

use PHPUnit\Framework\TestCase;
use SelcukMart\SQLBuilder\Builder\DeleteBuilder;

class DeleteBuilderTest extends TestCase
{
    public function testSimpleDelete(): void
    {
        $builder = DeleteBuilder::create()
            ->from('users')
            ->where('id', '=', 1);

        $sql = $builder->getSQL();
        $bindings = $builder->getBindings();

        $this->assertSame('DELETE FROM users WHERE id = :param_0', $sql);
        $this->assertSame(['param_0' => 1], $bindings);
    }

    public function testDeleteWithMultipleConditions(): void
    {
        $builder = DeleteBuilder::create()
            ->from('users')
            ->where('status', '=', 'inactive')
            ->where('created_at', '<', '2020-01-01');

        $sql = $builder->getSQL();

        $this->assertSame('DELETE FROM users WHERE status = :param_0 AND created_at < :param_1', $sql);
    }

    public function testDeleteWithOrWhere(): void
    {
        $builder = DeleteBuilder::create()
            ->from('users')
            ->where('status', '=', 'banned')
            ->orWhere('deleted', '=', true);

        $sql = $builder->getSQL();

        $this->assertStringContainsString('WHERE status = :param_0 OR deleted = :param_1', $sql);
    }

    public function testDeleteWithLimit(): void
    {
        $builder = DeleteBuilder::create()
            ->from('logs')
            ->where('created_at', '<', '2023-01-01')
            ->limit(1000);

        $sql = $builder->getSQL();

        $this->assertStringContainsString('LIMIT 1000', $sql);
    }
}
