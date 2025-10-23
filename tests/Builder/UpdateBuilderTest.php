<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Tests\Builder;

use PHPUnit\Framework\TestCase;
use SelcukMart\SQLBuilder\Builder\UpdateBuilder;

class UpdateBuilderTest extends TestCase
{
    public function testSimpleUpdate(): void
    {
        $builder = UpdateBuilder::create()
            ->table('users')
            ->set(['name' => 'John Updated', 'age' => 31])
            ->where('id', '=', 1);

        $sql = $builder->getSQL();
        $bindings = $builder->getBindings();

        $this->assertSame('UPDATE users SET name = :param_0, age = :param_1 WHERE id = :param_2', $sql);
        $this->assertSame([
            'param_0' => 'John Updated',
            'param_1' => 31,
            'param_2' => 1
        ], $bindings);
    }

    public function testUpdateWithMultipleWhereConditions(): void
    {
        $builder = UpdateBuilder::create()
            ->table('users')
            ->set(['status' => 'inactive'])
            ->where('last_login', '<', '2023-01-01')
            ->where('status', '=', 'active');

        $sql = $builder->getSQL();

        $this->assertStringContainsString('UPDATE users SET status = :param_0', $sql);
        $this->assertStringContainsString('WHERE last_login < :param_1 AND status = :param_2', $sql);
    }

    public function testUpdateWithLimit(): void
    {
        $builder = UpdateBuilder::create()
            ->table('users')
            ->set(['verified' => true])
            ->where('verified', '=', false)
            ->limit(100);

        $sql = $builder->getSQL();

        $this->assertStringContainsString('LIMIT 100', $sql);
    }
}
