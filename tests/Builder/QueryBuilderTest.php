<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Tests\Builder;

use PHPUnit\Framework\TestCase;
use SelcukMart\SQLBuilder\Builder\QueryBuilder;
use SelcukMart\SQLBuilder\Enums\JoinType;
use SelcukMart\SQLBuilder\Enums\OrderDirection;

class QueryBuilderTest extends TestCase
{
    public function testSimpleSelectQuery(): void
    {
        $query = QueryBuilder::create()
            ->select('id', 'name')
            ->from('users')
            ->getSQL();

        $this->assertSame('SELECT id, name FROM users', $query);
    }

    public function testSelectAllColumns(): void
    {
        $query = QueryBuilder::create()
            ->from('users')
            ->getSQL();

        $this->assertSame('SELECT * FROM users', $query);
    }

    public function testSelectWithTableAlias(): void
    {
        $query = QueryBuilder::create()
            ->select('u.id', 'u.name')
            ->from('users', 'u')
            ->getSQL();

        $this->assertSame('SELECT u.id, u.name FROM users AS u', $query);
    }

    public function testSelectWithWhere(): void
    {
        $builder = QueryBuilder::create()
            ->select('*')
            ->from('users')
            ->where('id', '=', 1);

        $sql = $builder->getSQL();
        $bindings = $builder->getBindings();

        $this->assertSame('SELECT * FROM users WHERE id = :param_0', $sql);
        $this->assertSame(['param_0' => 1], $bindings);
    }

    public function testSelectWithMultipleWhereConditions(): void
    {
        $builder = QueryBuilder::create()
            ->select('*')
            ->from('users')
            ->where('status', '=', 'active')
            ->where('age', '>', 18);

        $sql = $builder->getSQL();
        $bindings = $builder->getBindings();

        $this->assertSame('SELECT * FROM users WHERE status = :param_0 AND age > :param_1', $sql);
        $this->assertSame(['param_0' => 'active', 'param_1' => 18], $bindings);
    }

    public function testSelectWithOrWhere(): void
    {
        $builder = QueryBuilder::create()
            ->select('*')
            ->from('users')
            ->where('role', '=', 'admin')
            ->orWhere('role', '=', 'moderator');

        $sql = $builder->getSQL();

        $this->assertSame('SELECT * FROM users WHERE role = :param_0 OR role = :param_1', $sql);
    }

    public function testSelectWithInnerJoin(): void
    {
        $builder = QueryBuilder::create()
            ->select('u.name', 'p.title')
            ->from('users', 'u')
            ->innerJoin('posts', 'posts.user_id', '=', 'u.id', 'p');

        $sql = $builder->getSQL();

        $this->assertSame('SELECT u.name, p.title FROM users AS u INNER JOIN posts AS p ON posts.user_id = u.id', $sql);
    }

    public function testSelectWithLeftJoin(): void
    {
        $builder = QueryBuilder::create()
            ->select('*')
            ->from('users', 'u')
            ->leftJoin('profiles', 'profiles.user_id', '=', 'u.id', 'p');

        $sql = $builder->getSQL();

        $this->assertStringContainsString('LEFT JOIN', $sql);
    }

    public function testSelectWithGroupBy(): void
    {
        $query = QueryBuilder::create()
            ->select('status', 'COUNT(*)')
            ->from('users')
            ->groupBy('status')
            ->getSQL();

        $this->assertSame('SELECT status, COUNT(*) FROM users GROUP BY status', $query);
    }

    public function testSelectWithHaving(): void
    {
        $builder = QueryBuilder::create()
            ->select('status', 'COUNT(*)')
            ->from('users')
            ->groupBy('status')
            ->having('COUNT(*)', '>', 5);

        $sql = $builder->getSQL();

        $this->assertStringContainsString('HAVING COUNT(*) > :param_0', $sql);
    }

    public function testSelectWithOrderBy(): void
    {
        $query = QueryBuilder::create()
            ->select('*')
            ->from('users')
            ->orderBy('name', OrderDirection::ASC)
            ->orderBy('created_at', OrderDirection::DESC)
            ->getSQL();

        $this->assertSame('SELECT * FROM users ORDER BY name ASC, created_at DESC', $query);
    }

    public function testSelectWithLimit(): void
    {
        $query = QueryBuilder::create()
            ->select('*')
            ->from('users')
            ->limit(10)
            ->getSQL();

        $this->assertSame('SELECT * FROM users LIMIT 10', $query);
    }

    public function testSelectWithOffset(): void
    {
        $query = QueryBuilder::create()
            ->select('*')
            ->from('users')
            ->limit(10)
            ->offset(20)
            ->getSQL();

        $this->assertSame('SELECT * FROM users LIMIT 10 OFFSET 20', $query);
    }

    public function testSelectWithWhereIn(): void
    {
        $builder = QueryBuilder::create()
            ->select('*')
            ->from('users')
            ->whereIn('id', [1, 2, 3, 4, 5]);

        $sql = $builder->getSQL();
        $bindings = $builder->getBindings();

        $this->assertSame('SELECT * FROM users WHERE id IN (:param_0, :param_1, :param_2, :param_3, :param_4)', $sql);
        $this->assertCount(5, $bindings);
    }

    public function testSelectWithWhereBetween(): void
    {
        $builder = QueryBuilder::create()
            ->select('*')
            ->from('products')
            ->whereBetween('price', 10.00, 100.00);

        $sql = $builder->getSQL();
        $bindings = $builder->getBindings();

        $this->assertSame('SELECT * FROM products WHERE price BETWEEN :param_0 AND :param_1', $sql);
        $this->assertSame(['param_0' => 10.00, 'param_1' => 100.00], $bindings);
    }

    public function testSelectWithSubquery(): void
    {
        $subquery = QueryBuilder::create()
            ->select('id')
            ->from('users')
            ->where('status', '=', 'active');

        $query = QueryBuilder::create()
            ->select('*')
            ->fromSubquery($subquery, 'active_users')
            ->getSQL();

        $this->assertStringContainsString('FROM (SELECT id FROM users WHERE status = :param_0) AS active_users', $query);
    }

    public function testComplexQuery(): void
    {
        $builder = QueryBuilder::create()
            ->select('u.id', 'u.name', 'COUNT(p.id) as post_count')
            ->from('users', 'u')
            ->leftJoin('posts', 'p.user_id', '=', 'u.id', 'p')
            ->where('u.status', '=', 'active')
            ->where('u.age', '>=', 18)
            ->groupBy('u.id', 'u.name')
            ->having('COUNT(p.id)', '>', 5)
            ->orderBy('post_count', OrderDirection::DESC)
            ->limit(10);

        $sql = $builder->getSQL();
        $bindings = $builder->getBindings();

        $this->assertStringContainsString('SELECT u.id, u.name, COUNT(p.id) as post_count', $sql);
        $this->assertStringContainsString('LEFT JOIN posts AS p ON p.user_id = u.id', $sql);
        $this->assertStringContainsString('WHERE u.status = :param_0 AND u.age >= :param_1', $sql);
        $this->assertStringContainsString('GROUP BY u.id, u.name', $sql);
        $this->assertStringContainsString('HAVING COUNT(p.id) > :param_2', $sql);
        $this->assertStringContainsString('ORDER BY post_count DESC', $sql);
        $this->assertStringContainsString('LIMIT 10', $sql);
        $this->assertCount(3, $bindings);
    }

    public function testReset(): void
    {
        $builder = QueryBuilder::create()
            ->select('*')
            ->from('users')
            ->where('id', '=', 1);

        $this->assertNotEmpty($builder->getSQL());
        $this->assertNotEmpty($builder->getBindings());

        $builder->reset();

        $this->assertSame('SELECT *', $builder->getSQL());
        $this->assertEmpty($builder->getBindings());
    }
}
