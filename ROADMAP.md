# SQLBuilder Modernization Roadmap

## Project Goals

Transform SQLBuilder into a modern PHP 8.1+ library with:
- Fluent Builder Pattern with method chaining
- Full Symfony & Laravel integration
- Enhanced type safety and security
- Comprehensive test coverage
- Modern development practices

---

## Phase 1: Foundation & Setup (Week 1)

### 1.1 Project Structure Modernization
- [ ] Reorganize to PSR-4 standard structure
  ```
  /src/              # All source code
  /tests/            # PHPUnit tests
  /examples/         # Usage examples
  /docs/             # Documentation
  ```
- [ ] Move SQLBuilder/* to src/*
- [ ] Update composer.json autoloading paths
- [ ] Add phpunit.xml configuration
- [ ] Add .editorconfig for consistent formatting

### 1.2 Dependency Management
- [ ] Update composer.json to require PHP ^8.1
- [ ] Move phpunit/phpunit to require-dev
- [ ] Update/replace jdorn/sql-formatter (consider doctrine/sql-formatter)
- [ ] Update brick/varexporter to ^0.4
- [ ] Add roave/security-advisories to require-dev
- [ ] Generate and commit composer.lock

### 1.3 Development Tools
- [ ] Add phpstan/phpstan for static analysis
- [ ] Add phpcs/phpcbf for code style
- [ ] Add psalm for additional type checking
- [ ] Configure GitHub Actions CI/CD
- [ ] Add code coverage reporting (codecov/coveralls)

**Deliverables:**
- Modern project structure
- Updated dependencies
- CI/CD pipeline
- Quality assurance tools

---

## Phase 2: Core Architecture Refactoring (Week 2-3)

### 2.1 Builder Pattern Implementation

**Current (Array-based):**
```php
$sql_generator = [
    ['type' => 'SELECT', 'a.*', 'b' => 'name'],
    ['type' => 'FROM', 'users', 'AS u'],
    ['type' => 'WHERE', 'id' => "id = 1"]
];
$sql = new SQLBuilder();
$sql->build($sql_generator);
```

**New (Fluent Builder):**
```php
$query = SQLBuilder::create()
    ->select('a.*')
    ->select('b.name')
    ->from('users', 'u')
    ->where('id', '=', 1)
    ->getSQL();
```

**Implementation Tasks:**
- [ ] Create QueryBuilder base class
- [ ] Implement SelectBuilder with fluent methods
- [ ] Implement InsertBuilder with fluent methods
- [ ] Implement UpdateBuilder with fluent methods
- [ ] Implement DeleteBuilder with fluent methods
- [ ] Create JoinBuilder for complex joins
- [ ] Implement WhereBuilder for complex conditions
- [ ] Support method chaining for all operations

### 2.2 PHP 8.1+ Modernization

**Features to Implement:**
- [ ] Constructor property promotion
- [ ] Readonly properties for immutable data
- [ ] Enum types for SQL operations (SelectType, JoinType, etc.)
- [ ] Union types for flexible parameters
- [ ] Named arguments support
- [ ] declare(strict_types=1) in all files
- [ ] Full return type declarations
- [ ] Parameter type declarations

**Example:**
```php
enum JoinType: string {
    case INNER = 'INNER JOIN';
    case LEFT = 'LEFT JOIN';
    case RIGHT = 'RIGHT JOIN';
    case FULL_OUTER = 'FULL OUTER JOIN';
    case CROSS = 'CROSS JOIN';
}

class QueryBuilder {
    public function __construct(
        private readonly ConnectionInterface $connection,
        private array $bindings = []
    ) {}
}
```

### 2.3 Security Enhancements

- [ ] Implement ParameterBinding class
- [ ] Auto-escape values by default
- [ ] Add raw() method for intentional raw SQL
- [ ] Implement prepared statement support
- [ ] Add SQL injection tests
- [ ] Document security best practices

**Example:**
```php
// Secure by default
$builder->where('name', '=', $userInput); // Auto-binds parameter

// Explicit raw SQL when needed
$builder->whereRaw('created_at > NOW() - INTERVAL 1 DAY');
```

### 2.4 Type Safety Improvements

- [ ] Fix _sizeof() return type issue
- [ ] Add strict type checking throughout
- [ ] Create value objects for complex types
- [ ] Implement Result/Option types for error handling
- [ ] Remove global variable usage
- [ ] Add PHPStan level 8 compliance

**Deliverables:**
- Fluent builder API
- PHP 8.1+ codebase
- Enhanced security
- Type-safe implementation

---

## Phase 3: Framework Integration (Week 4)

### 3.1 Laravel Integration

**Package Structure:**
```
/src/Laravel/
    SQLBuilderServiceProvider.php
    Facades/SQLBuilder.php
    config/sqlbuilder.php
```

**Features:**
- [ ] Create Laravel Service Provider
- [ ] Register as singleton service
- [ ] Add Facade support
- [ ] Integration with Laravel's DB connection
- [ ] Configuration file publishing
- [ ] Artisan commands (if needed)
- [ ] Laravel-specific documentation

**Usage Example:**
```php
// config/app.php
'providers' => [
    SelcukMart\Laravel\SQLBuilderServiceProvider::class,
],

// In controller
use SelcukMart\Facades\SQLBuilder;

$query = SQLBuilder::select('*')
    ->from('users')
    ->where('active', '=', true)
    ->get();
```

### 3.2 Symfony Integration

**Bundle Structure:**
```
/src/Symfony/
    SelcukMartSQLBuilderBundle.php
    DependencyInjection/
        Configuration.php
        SelcukMartSQLBuilderExtension.php
    Resources/config/
        services.yaml
```

**Features:**
- [ ] Create Symfony Bundle
- [ ] Service container integration
- [ ] Configuration via config/packages/
- [ ] Integration with Doctrine DBAL
- [ ] Bundle documentation
- [ ] Symfony Flex recipe (optional)

**Usage Example:**
```php
// config/bundles.php
return [
    SelcukMart\Symfony\SelcukMartSQLBuilderBundle::class => ['all' => true],
];

// In controller/service
public function __construct(
    private SQLBuilder $sqlBuilder
) {}

$query = $this->sqlBuilder->select('*')
    ->from('users')
    ->where('active', '=', true)
    ->getSQL();
```

### 3.3 Standalone Usage

- [ ] Ensure library works without frameworks
- [ ] Add PSR-11 container support
- [ ] Add PSR-3 logger support
- [ ] Provide simple instantiation examples

**Deliverables:**
- Laravel package
- Symfony bundle
- Framework-agnostic core
- Integration documentation

---

## Phase 4: Testing & Quality (Week 5)

### 4.1 Test Suite Refactoring

- [ ] Update existing tests for new API
- [ ] Maintain backward compatibility tests (deprecated)
- [ ] Add builder pattern tests
- [ ] Add framework integration tests
- [ ] Add security tests (injection attempts)
- [ ] Add edge case tests
- [ ] Achieve 90%+ code coverage

**Test Structure:**
```
/tests/
    Unit/
        QueryBuilderTest.php
        SelectBuilderTest.php
        WhereBuilderTest.php
        ...
    Integration/
        LaravelIntegrationTest.php
        SymfonyIntegrationTest.php
    Feature/
        ComplexQueryTest.php
        SubqueryTest.php
        SecurityTest.php
```

### 4.2 Quality Assurance

- [ ] Run PHPStan level 8
- [ ] Run Psalm
- [ ] Fix all code style issues (PSR-12)
- [ ] Add mutation testing (infection/infection)
- [ ] Performance benchmarking
- [ ] Memory profiling

### 4.3 Continuous Integration

**GitHub Actions Workflow:**
```yaml
- PHP 8.1, 8.2, 8.3 matrix
- Multiple OS (ubuntu, windows, macos)
- Dependency variations (lowest, locked, highest)
- Code coverage reporting
- Static analysis
- Security scanning
```

**Deliverables:**
- Comprehensive test suite
- High code coverage
- Automated quality checks
- Performance benchmarks

---

## Phase 5: Documentation & Migration (Week 6)

### 5.1 Documentation Updates

- [ ] **README.md** - Quick start with new API
- [ ] **UPGRADE.md** - Migration guide from v1 to v2
- [ ] **SECURITY.md** - Security policy and best practices
- [ ] **CONTRIBUTING.md** - Contribution guidelines
- [ ] **CHANGELOG.md** - Version history
- [ ] **LICENSE** - Full license text (MIT)

### 5.2 API Documentation

- [ ] PHPDoc blocks for all public methods
- [ ] Generate API docs (phpDocumentor)
- [ ] Create usage examples for common scenarios
- [ ] Document all configuration options
- [ ] Add troubleshooting guide

### 5.3 Migration Support

**Backward Compatibility Layer:**
- [ ] Create LegacyArrayBuilder adapter
- [ ] Deprecation notices for old API
- [ ] Migration script for code updates
- [ ] Side-by-side comparison examples

**Example Migration:**
```php
// OLD API (still works but deprecated)
$sql = new SQLBuilder();
$sql->build([
    ['type' => 'SELECT', '*'],
    ['type' => 'FROM', 'users']
]);

// NEW API (recommended)
$query = SQLBuilder::create()
    ->select('*')
    ->from('users')
    ->getSQL();
```

### 5.4 Examples & Tutorials

- [ ] Basic CRUD examples
- [ ] Complex query examples
- [ ] Subquery examples
- [ ] Laravel integration tutorial
- [ ] Symfony integration tutorial
- [ ] Performance optimization guide
- [ ] Security best practices guide

**Deliverables:**
- Complete documentation
- Migration guide
- Example applications
- Video tutorials (optional)

---

## Phase 6: Release & Distribution (Week 7)

### 6.1 Release Preparation

- [ ] Create version 2.0.0-beta1
- [ ] Tag release in git
- [ ] Update packagist listing
- [ ] Create GitHub release notes
- [ ] Announce beta testing period

### 6.2 Beta Testing

- [ ] Community feedback collection
- [ ] Bug fixes from beta testers
- [ ] Performance optimization
- [ ] Documentation improvements

### 6.3 Final Release

- [ ] Release version 2.0.0
- [ ] Update packagist
- [ ] Announce on social media
- [ ] Submit to PHP Weekly/newsletters
- [ ] Create demo application

**Deliverables:**
- Stable v2.0.0 release
- Public announcement
- Demo applications
- Community engagement

---

## Technical Debt & Cleanup

### Items to Remove/Fix

- [x] ❌ Global variable ($sayga) usage
- [x] ❌ Empty destructors
- [x] ❌ Turkish language comments
- [x] ❌ Incomplete Migration tool
- [x] ❌ Examples in src directory
- [x] ❌ Type safety issues
- [x] ❌ Missing return types

### Items to Keep (But Modernize)

- ✅ Hook system (modernize with events)
- ✅ Depth tracking for subqueries
- ✅ SQL formatting support
- ✅ Command pattern architecture

---

## Success Metrics

### Code Quality
- [ ] PHPStan level 8: Pass
- [ ] Code coverage: > 90%
- [ ] Psalm: No errors
- [ ] PHP-CS-Fixer: PSR-12 compliant

### Performance
- [ ] Benchmark: < 5ms for simple queries
- [ ] Benchmark: < 50ms for complex queries with subqueries
- [ ] Memory: < 2MB for typical usage

### Adoption
- [ ] 100+ GitHub stars (from current unknown)
- [ ] 1000+ monthly downloads on Packagist
- [ ] 5+ community contributions
- [ ] Framework documentation mentions

### Documentation
- [ ] README: Comprehensive quick start
- [ ] API docs: 100% public methods documented
- [ ] Examples: 20+ real-world scenarios
- [ ] Tutorials: Laravel + Symfony integration

---

## Risk Mitigation

### Breaking Changes
**Risk:** Existing users cannot upgrade
**Mitigation:**
- Maintain v1.x branch for bug fixes
- Provide backward compatibility layer
- Clear migration documentation
- Deprecation warnings before removal

### Framework Compatibility
**Risk:** Framework updates break integration
**Mitigation:**
- Minimal framework coupling
- Use framework interfaces, not implementations
- Test against multiple framework versions
- Monitor framework release cycles

### Security Issues
**Risk:** SQL injection vulnerabilities
**Mitigation:**
- Security-first design
- Mandatory code reviews for security-sensitive code
- Automated security scanning
- Bug bounty program (if popular)

### Community Adoption
**Risk:** Users don't migrate to new API
**Mitigation:**
- Clear benefits documentation
- Easy migration path
- Community engagement
- Showcase projects using new API

---

## Timeline Summary

| Phase | Duration | Key Milestone |
|-------|----------|---------------|
| 1. Foundation | Week 1 | Modern project structure |
| 2. Core Refactoring | Week 2-3 | Fluent builder API |
| 3. Framework Integration | Week 4 | Laravel & Symfony support |
| 4. Testing | Week 5 | 90%+ coverage |
| 5. Documentation | Week 6 | Complete docs |
| 6. Release | Week 7 | v2.0.0 stable |

**Total Estimated Time: 7 weeks**

---

## Post-Release Roadmap

### Version 2.1 (Month 2-3)
- [ ] Query result objects
- [ ] Pagination support
- [ ] Query caching layer
- [ ] Schema builder integration

### Version 2.2 (Month 4-5)
- [ ] GraphQL query support
- [ ] Multi-database support (PostgreSQL, SQLite)
- [ ] Query optimization analyzer
- [ ] Visual query builder (web UI)

### Version 3.0 (Month 6+)
- [ ] ORM integration
- [ ] Migration system
- [ ] Seeding support
- [ ] Full database management suite

---

## Resources Needed

### Development
- 1 Senior PHP Developer (full-time)
- 1 Junior PHP Developer (part-time)
- Access to Laravel & Symfony test environments

### Testing
- CI/CD credits (GitHub Actions)
- Multiple PHP version environments
- Database servers for integration tests

### Documentation
- Technical writer (consulting)
- Video production for tutorials (optional)

### Community
- Community manager for feedback
- Social media presence
- Forum/Discord for support

---

## Conclusion

This roadmap transforms SQLBuilder from a functional library into a modern, secure, and framework-integrated SQL building solution. The phased approach minimizes risk while delivering continuous value to users.

**Next Steps:**
1. Review and approve roadmap
2. Set up development environment
3. Begin Phase 1 implementation
4. Establish weekly progress reviews

**Questions or feedback?** Open an issue or discussion on GitHub.
