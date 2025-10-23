<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Tests\Builder;

use PHPUnit\Framework\TestCase;
use SelcukMart\SQLBuilder\Builder\InsertBuilder;

class InsertBuilderTest extends TestCase
{
    public function testSimpleInsert(): void
    {
        $builder = InsertBuilder::create()
            ->into('users')
            ->values([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'age' => 30
            ]);

        $sql = $builder->getSQL();
        $bindings = $builder->getBindings();

        $this->assertSame('INSERT INTO users (name, email, age) VALUES (:param_0, :param_1, :param_2)', $sql);
        $this->assertSame([
            'param_0' => 'John Doe',
            'param_1' => 'john@example.com',
            'param_2' => 30
        ], $bindings);
    }

    public function testMultipleInsert(): void
    {
        $builder = InsertBuilder::create()
            ->into('users')
            ->multipleValues([
                ['name' => 'John', 'email' => 'john@example.com'],
                ['name' => 'Jane', 'email' => 'jane@example.com'],
                ['name' => 'Bob', 'email' => 'bob@example.com']
            ]);

        $sql = $builder->getSQL();
        $bindings = $builder->getBindings();

        $this->assertStringContainsString('INSERT INTO users (name, email) VALUES', $sql);
        $this->assertStringContainsString('(:param_0, :param_1)', $sql);
        $this->assertStringContainsString('(:param_2, :param_3)', $sql);
        $this->assertStringContainsString('(:param_4, :param_5)', $sql);
        $this->assertCount(6, $bindings);
    }
}
