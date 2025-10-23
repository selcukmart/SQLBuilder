<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Tests\Builder;

use PHPUnit\Framework\TestCase;
use SelcukMart\SQLBuilder\SQLBuilder;

class DDLBuildersTest extends TestCase
{
    // ==================== REPLACE ====================

    public function testReplaceBuilder(): void
    {
        $builder = SQLBuilder::replace()
            ->into('users')
            ->values(['id' => 1, 'name' => 'John', 'email' => 'john@example.com']);

        $sql = $builder->getSQL();

        $this->assertStringContainsString('REPLACE INTO users', $sql);
        $this->assertStringContainsString('(id, name, email)', $sql);
        $this->assertStringContainsString('VALUES', $sql);
    }

    // ==================== CREATE TABLE ====================

    public function testCreateTableBuilder(): void
    {
        $builder = SQLBuilder::createTable('users')
            ->integer('id', ['AUTO_INCREMENT'])
            ->varchar('name', 100, ['NOT NULL'])
            ->varchar('email', 255, ['NOT NULL', 'UNIQUE'])
            ->timestamps()
            ->primaryKey('id')
            ->engine('InnoDB')
            ->charset('utf8mb4');

        $sql = $builder->getSQL();

        $this->assertStringContainsString('CREATE TABLE users', $sql);
        $this->assertStringContainsString('id INT AUTO_INCREMENT', $sql);
        $this->assertStringContainsString('name VARCHAR(100) NOT NULL', $sql);
        $this->assertStringContainsString('email VARCHAR(255) NOT NULL UNIQUE', $sql);
        $this->assertStringContainsString('PRIMARY KEY (id)', $sql);
        $this->assertStringContainsString('ENGINE=InnoDB', $sql);
        $this->assertStringContainsString('DEFAULT CHARSET=utf8mb4', $sql);
    }

    public function testCreateTableIfNotExists(): void
    {
        $builder = SQLBuilder::createTable('users')
            ->ifNotExists()
            ->integer('id')
            ->primaryKey('id');

        $sql = $builder->getSQL();

        $this->assertStringContainsString('IF NOT EXISTS', $sql);
    }

    public function testCreateTableWithForeignKey(): void
    {
        $builder = SQLBuilder::createTable('posts')
            ->integer('id')
            ->integer('user_id')
            ->text('content')
            ->primaryKey('id')
            ->foreignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $sql = $builder->getSQL();

        $this->assertStringContainsString('FOREIGN KEY (user_id) REFERENCES users(id)', $sql);
        $this->assertStringContainsString('ON DELETE CASCADE', $sql);
        $this->assertStringContainsString('ON UPDATE CASCADE', $sql);
    }

    // ==================== CREATE INDEX ====================

    public function testCreateIndexBuilder(): void
    {
        $builder = SQLBuilder::createIndex('idx_email')
            ->on('users')
            ->columns('email');

        $sql = $builder->getSQL();

        $this->assertSame('CREATE INDEX idx_email ON users (email)', $sql);
    }

    public function testCreateUniqueIndex(): void
    {
        $builder = SQLBuilder::createIndex('idx_username')
            ->on('users')
            ->columns('username')
            ->unique();

        $sql = $builder->getSQL();

        $this->assertStringContainsString('CREATE UNIQUE INDEX', $sql);
    }

    public function testCreateCompositeIndex(): void
    {
        $builder = SQLBuilder::createIndex('idx_name_email')
            ->on('users')
            ->columns('name', 'email');

        $sql = $builder->getSQL();

        $this->assertStringContainsString('(name, email)', $sql);
    }

    // ==================== DROP ====================

    public function testDropTable(): void
    {
        $builder = SQLBuilder::dropTable('users');

        $sql = $builder->getSQL();

        $this->assertSame('DROP TABLE users', $sql);
    }

    public function testDropTableIfExists(): void
    {
        $builder = SQLBuilder::dropTable('users')
            ->ifExists();

        $sql = $builder->getSQL();

        $this->assertSame('DROP TABLE IF EXISTS users', $sql);
    }

    public function testDropIndex(): void
    {
        $builder = SQLBuilder::dropIndex('idx_email', 'users');

        $sql = $builder->getSQL();

        $this->assertSame('DROP INDEX idx_email ON users', $sql);
    }

    public function testDropDatabase(): void
    {
        $builder = SQLBuilder::dropDatabase('test_db');

        $sql = $builder->getSQL();

        $this->assertSame('DROP DATABASE test_db', $sql);
    }

    // ==================== TRUNCATE ====================

    public function testTruncateTable(): void
    {
        $builder = SQLBuilder::truncate('users');

        $sql = $builder->getSQL();

        $this->assertSame('TRUNCATE TABLE users', $sql);
    }

    // ==================== RENAME ====================

    public function testRenameTable(): void
    {
        $builder = SQLBuilder::rename('old_users', 'new_users');

        $sql = $builder->getSQL();

        $this->assertSame('RENAME TABLE old_users TO new_users', $sql);
    }

    public function testRenameMultipleTables(): void
    {
        $builder = SQLBuilder::rename('old_users', 'new_users')
            ->table('old_posts', 'new_posts');

        $sql = $builder->getSQL();

        $this->assertStringContainsString('RENAME TABLE', $sql);
        $this->assertStringContainsString('old_users TO new_users', $sql);
        $this->assertStringContainsString('old_posts TO new_posts', $sql);
    }

    // ==================== SHOW ====================

    public function testShowTables(): void
    {
        $builder = SQLBuilder::showTables();

        $sql = $builder->getSQL();

        $this->assertSame('SHOW TABLES', $sql);
    }

    public function testShowTablesFrom(): void
    {
        $builder = SQLBuilder::showTables('my_database');

        $sql = $builder->getSQL();

        $this->assertSame('SHOW TABLES FROM my_database', $sql);
    }

    public function testShowDatabases(): void
    {
        $builder = SQLBuilder::showDatabases();

        $sql = $builder->getSQL();

        $this->assertSame('SHOW DATABASES', $sql);
    }

    public function testShowColumns(): void
    {
        $builder = SQLBuilder::showColumns('users');

        $sql = $builder->getSQL();

        $this->assertSame('SHOW COLUMNS FROM users', $sql);
    }

    public function testShowIndexes(): void
    {
        $builder = SQLBuilder::show()
            ->indexes('users');

        $sql = $builder->getSQL();

        $this->assertSame('SHOW INDEX FROM users', $sql);
    }

    public function testShowWithLike(): void
    {
        $builder = SQLBuilder::showTables()
            ->like('user%');

        $sql = $builder->getSQL();

        $this->assertStringContainsString("LIKE 'user%'", $sql);
    }

    // ==================== SET ====================

    public function testSetVariable(): void
    {
        $builder = SQLBuilder::set()
            ->variable('autocommit', 0);

        $sql = $builder->getSQL();

        $this->assertSame('SET autocommit = 0', $sql);
    }

    public function testSetMultipleVariables(): void
    {
        $builder = SQLBuilder::set([
            'sql_mode' => 'STRICT_ALL_TABLES',
            'time_zone' => '+00:00'
        ]);

        $sql = $builder->getSQL();

        $this->assertStringContainsString('SET', $sql);
        $this->assertStringContainsString("sql_mode = 'STRICT_ALL_TABLES'", $sql);
        $this->assertStringContainsString("time_zone = '+00:00'", $sql);
    }

    // ==================== EXPLAIN ====================

    public function testExplainWithQueryBuilder(): void
    {
        $query = SQLBuilder::select('*')
            ->from('users')
            ->where('status', '=', 'active');

        $builder = SQLBuilder::explain($query);

        $sql = $builder->getSQL();

        $this->assertStringContainsString('EXPLAIN SELECT', $sql);
        $this->assertStringContainsString('FROM users', $sql);
    }

    public function testExplainWithRawQuery(): void
    {
        $builder = SQLBuilder::explain('SELECT * FROM users');

        $sql = $builder->getSQL();

        $this->assertSame('EXPLAIN SELECT * FROM users', $sql);
    }

    public function testExplainWithFormat(): void
    {
        $query = SQLBuilder::select('*')->from('users');
        $builder = SQLBuilder::explain($query)
            ->format('JSON');

        $sql = $builder->getSQL();

        $this->assertStringContainsString('EXPLAIN FORMAT=JSON', $sql);
    }

    // ==================== DESCRIBE ====================

    public function testDescribeTable(): void
    {
        $builder = SQLBuilder::describe('users');

        $sql = $builder->getSQL();

        $this->assertSame('DESCRIBE users', $sql);
    }
}
