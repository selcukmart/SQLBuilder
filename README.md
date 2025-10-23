# SQLBuilder - Modern PHP 8.1+ SQL Query Builder

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Tests](https://img.shields.io/badge/tests-passing-brightgreen.svg)](tests/)

Modern, fluent SQL query builder for PHP 8.1+ with built-in security, framework integration, and comprehensive SQL support.

## ✨ Features

- **🔒 Secure by Default** - Automatic parameter binding prevents SQL injection
- **⛓️ Fluent API** - Intuitive method chaining for readable code
- **🎯 Type-Safe** - Full PHP 8.1+ type declarations with enums
- **🚀 Framework Ready** - Native Laravel & Symfony integration
- **📦 Complete SQL Support** - DML, DDL, and utility operations
- **🧪 Well Tested** - 90%+ code coverage
- **📖 Well Documented** - Comprehensive guides and examples

---

## 📋 Supported SQL Operations

### ✅ DML (Data Manipulation)
- ✅ SELECT - With joins, subqueries, grouping
- ✅ INSERT - Single & multiple rows
- ✅ UPDATE - With conditions
- ✅ DELETE - With conditions
- ✅ REPLACE - MySQL REPLACE operation
- ✅ TRUNCATE - Fast table truncation

### ✅ DDL (Data Definition)
- ✅ CREATE TABLE - Full table creation with constraints
- ✅ CREATE INDEX - Simple & composite indexes
- ✅ DROP - Tables, indexes, databases
- ✅ RENAME - Table renaming

### ✅ Utility Operations
- ✅ SHOW - Tables, databases, columns, indexes
- ✅ DESCRIBE - Table structure
- ✅ EXPLAIN - Query analysis
- ✅ SET - Session variables

---

## 📦 Installation

```bash
composer require selcukmart/sqlbuilder
```

### Requirements

- PHP 8.1 or higher
- Composer 2.0+

---

## 🚀 Quick Start

```php
use SelcukMart\SQLBuilder\SQLBuilder;

// Simple SELECT query
$query = SQLBuilder::table('users')
    ->select('id', 'name', 'email')
    ->where('status', '=', 'active')
    ->orderBy('created_at')
    ->limit(10)
    ->getSQL();

echo $query;
// SELECT id, name, email FROM users WHERE status = :param_0 ORDER BY created_at ASC LIMIT 10

// Get parameter bindings for prepared statements
$bindings = $builder->getBindings();
// ['param_0' => 'active']
```

---

## 📖 Complete Usage Guide

### SELECT Queries

#### Basic SELECT

```php
// Select all columns
$query = SQLBuilder::table('users')->getSQL();
// SELECT * FROM users

// Select specific columns
$query = SQLBuilder::select('id', 'name', 'email')
    ->from('users')
    ->getSQL();
// SELECT id, name, email FROM users
```

#### WHERE Clauses

```php
// Simple WHERE
$builder = SQLBuilder::table('users')
    ->where('age', '>', 18)
    ->where('status', '=', 'active');

// WHERE with OR
$builder = SQLBuilder::table('users')
    ->where('role', '=', 'admin')
    ->orWhere('role', '=', 'moderator');

// WHERE IN
$builder = SQLBuilder::table('users')
    ->whereIn('id', [1, 2, 3, 4, 5]);

// WHERE BETWEEN
$builder = SQLBuilder::table('products')
    ->whereBetween('price', 10.00, 100.00);
```

#### JOIN Operations

```php
// INNER JOIN
$query = SQLBuilder::select('u.name', 'p.title')
    ->from('users', 'u')
    ->innerJoin('posts', 'p.user_id', '=', 'u.id', 'p')
    ->getSQL();

// LEFT JOIN
$query = SQLBuilder::table('users', 'u')
    ->leftJoin('profiles', 'profiles.user_id', '=', 'u.id', 'pr')
    ->getSQL();

// Multiple JOINS
$query = SQLBuilder::table('users', 'u')
    ->leftJoin('profiles', 'profiles.user_id', '=', 'u.id', 'pr')
    ->leftJoin('settings', 'settings.user_id', '=', 'u.id', 's')
    ->where('u.active', '=', true)
    ->getSQL();
```

#### GROUP BY & HAVING

```php
use SelcukMart\SQLBuilder\Enums\OrderDirection;

$query = SQLBuilder::select('category', 'COUNT(*) as total')
    ->from('products')
    ->groupBy('category')
    ->having('COUNT(*)', '>', 5)
    ->orderBy('total', OrderDirection::DESC)
    ->getSQL();
```

---

### INSERT

```php
// Single row INSERT
$builder = SQLBuilder::insert()
    ->into('users')
    ->values([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'age' => 30
    ]);

// Multiple rows INSERT
$builder = SQLBuilder::insert()
    ->into('users')
    ->multipleValues([
        ['name' => 'John', 'email' => 'john@example.com'],
        ['name' => 'Jane', 'email' => 'jane@example.com'],
        ['name' => 'Bob', 'email' => 'bob@example.com']
    ]);
```

---

### UPDATE

```php
// Simple UPDATE
$builder = SQLBuilder::update('users')
    ->set([
        'name' => 'John Updated',
        'email' => 'john.new@example.com'
    ])
    ->where('id', '=', 1);

// UPDATE with multiple conditions
$builder = SQLBuilder::update('users')
    ->set(['status' => 'inactive'])
    ->where('last_login', '<', '2023-01-01')
    ->where('email_verified', '=', false)
    ->limit(100);
```

---

### DELETE

```php
// Simple DELETE
$builder = SQLBuilder::delete('users')
    ->where('id', '=', 1);

// DELETE with multiple conditions
$builder = SQLBuilder::delete('logs')
    ->where('created_at', '<', '2023-01-01')
    ->limit(1000);
```

---

### REPLACE

```php
// REPLACE works like INSERT but replaces existing rows
$builder = SQLBuilder::replace()
    ->into('cache')
    ->values([
        'key' => 'user_123',
        'value' => 'cached_data',
        'expires_at' => '2024-12-31'
    ]);
```

---

### CREATE TABLE

```php
// Basic table creation
$builder = SQLBuilder::createTable('users')
    ->integer('id', ['AUTO_INCREMENT'])
    ->varchar('name', 100, ['NOT NULL'])
    ->varchar('email', 255, ['NOT NULL', 'UNIQUE'])
    ->text('bio')
    ->timestamps() // Adds created_at and updated_at
    ->primaryKey('id')
    ->engine('InnoDB')
    ->charset('utf8mb4');

// With foreign key
$builder = SQLBuilder::createTable('posts')
    ->integer('id', ['AUTO_INCREMENT'])
    ->integer('user_id', ['NOT NULL'])
    ->text('content')
    ->primaryKey('id')
    ->foreignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
```

---

### CREATE INDEX

```php
// Simple index
$sql = SQLBuilder::createIndex('idx_email')
    ->on('users')
    ->columns('email')
    ->getSQL();

// Unique index
$sql = SQLBuilder::createIndex('idx_username')
    ->on('users')
    ->columns('username')
    ->unique()
    ->getSQL();
```

---

### DROP Operations

```php
// Drop table
$sql = SQLBuilder::dropTable('old_table')->getSQL();

// Drop table if exists
$sql = SQLBuilder::dropTable('users')->ifExists()->getSQL();

// Drop index
$sql = SQLBuilder::dropIndex('idx_email', 'users')->getSQL();

// Drop database
$sql = SQLBuilder::dropDatabase('old_db')->ifExists()->getSQL();
```

---

### RENAME

```php
// Rename single table
$sql = SQLBuilder::rename('old_users', 'new_users')->getSQL();

// Rename multiple tables
$sql = SQLBuilder::rename('old_users', 'new_users')
    ->table('old_posts', 'new_posts')
    ->getSQL();
```

---

### TRUNCATE

```php
// TRUNCATE removes all rows from a table
$sql = SQLBuilder::truncate('logs')->getSQL();
```

---

### SHOW Commands

```php
// Show tables
$sql = SQLBuilder::showTables()->getSQL();

// Show databases
$sql = SQLBuilder::showDatabases()->getSQL();

// Show columns
$sql = SQLBuilder::showColumns('users')->getSQL();

// Show indexes
$sql = SQLBuilder::show()->indexes('users')->getSQL();
```

---

### DESCRIBE

```php
// Describe table structure
$sql = SQLBuilder::describe('users')->getSQL();
```

---

### EXPLAIN

```php
// Explain a query
$query = SQLBuilder::select('*')
    ->from('users')
    ->where('status', '=', 'active');

$sql = SQLBuilder::explain($query)->getSQL();

// Explain with JSON format
$sql = SQLBuilder::explain($query)->format('JSON')->getSQL();
```

---

### SET

```php
// Set session variables
$sql = SQLBuilder::set([
    'sql_mode' => 'STRICT_ALL_TABLES',
    'time_zone' => '+00:00',
    'autocommit' => 1
])->getSQL();
```

---

## 🔒 Security

**All values are automatically bound as parameters!**

```php
// ✅ SAFE - Values are automatically bound
$builder = SQLBuilder::table('users')
    ->where('email', '=', $_POST['email'])
    ->where('age', '>', $_POST['age']);

$sql = $builder->getSQL();
// SELECT * FROM users WHERE email = :param_0 AND age > :param_1

$bindings = $builder->getBindings();
// ['param_0' => 'user@example.com', 'param_1' => 25]
```

---

## 🎨 Framework Integration

### Laravel

```php
use SelcukMart\SQLBuilder\Laravel\Facades\SQLBuilder;

class UserController extends Controller
{
    public function index()
    {
        $builder = SQLBuilder::table('users')
            ->select('*')
            ->where('active', '=', true);

        $users = DB::select($builder->getSQL(), $builder->getBindings());

        return view('users.index', compact('users'));
    }
}
```

### Symfony

```php
use SelcukMart\SQLBuilder\SQLBuilder;

class UserService
{
    public function __construct(
        private SQLBuilder $sqlBuilder,
        private Connection $connection
    ) {}

    public function getActiveUsers(): array
    {
        $builder = $this->sqlBuilder->select('*')
            ->from('users')
            ->where('status', '=', 'active');

        return $this->connection->fetchAllAssociative(
            $builder->getSQL(),
            $builder->getBindings()
        );
    }
}
```

---

## 🚀 Advanced Features

### Subqueries

```php
// Subquery in FROM
$subquery = SQLBuilder::select('id', 'name')
    ->from('users')
    ->where('status', '=', 'active');

$query = SQLBuilder::select('*')
    ->fromSubquery($subquery, 'active_users')
    ->where('active_users.age', '>', 18)
    ->getSQL();
```

### Complex Queries

```php
$builder = SQLBuilder::select('u.id', 'u.name', 'COUNT(p.id) as post_count')
    ->from('users', 'u')
    ->leftJoin('posts', 'p.user_id', '=', 'u.id', 'p')
    ->where('u.status', '=', 'active')
    ->where('u.age', '>=', 18)
    ->groupBy('u.id', 'u.name')
    ->having('COUNT(p.id)', '>', 5)
    ->orderBy('post_count', OrderDirection::DESC)
    ->limit(10);
```

---

## 📚 Documentation

- **[UPGRADE.md](UPGRADE.md)** - Migration guide from v1 to v2
- **[ROADMAP.md](ROADMAP.md)** - Development roadmap
- **[EXECUTIVE_SUMMARY.md](EXECUTIVE_SUMMARY.md)** - Project overview

---

## 🧪 Testing

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run static analysis
composer phpstan

# Check code style
composer cs-check
```

---

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details.

---

## 🙏 Credits

Created by [Selcuk Mart](https://github.com/selcukmart)

---

**Made with ❤️ for the PHP community**
