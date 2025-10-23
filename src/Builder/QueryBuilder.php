<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use SelcukMart\SQLBuilder\Enums\JoinType;
use SelcukMart\SQLBuilder\Enums\OrderDirection;
use Doctrine\SqlFormatter\SqlFormatter;

class QueryBuilder implements BuilderInterface
{
    private array $select = [];
    private array $from = [];
    private array $joins = [];
    private array $where = [];
    private array $groupBy = [];
    private array $having = [];
    private array $orderBy = [];
    private ?int $limit = null;
    private ?int $offset = null;
    private array $bindings = [];
    private int $bindingCounter = 0;

    public function __construct(
        private readonly ?SqlFormatter $formatter = null
    ) {
    }

    /**
     * Create a new QueryBuilder instance
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Add columns to SELECT clause
     *
     * @param string ...$columns
     */
    public function select(string ...$columns): self
    {
        $this->select = array_merge($this->select, $columns);
        return $this;
    }

    /**
     * Set the FROM clause
     *
     * @param string $table Table name
     * @param string|null $alias Optional table alias
     */
    public function from(string $table, ?string $alias = null): self
    {
        $this->from = [
            'table' => $table,
            'alias' => $alias
        ];
        return $this;
    }

    /**
     * Add a subquery as FROM clause
     *
     * @param QueryBuilder $subquery
     * @param string $alias
     */
    public function fromSubquery(QueryBuilder $subquery, string $alias): self
    {
        $this->from = [
            'subquery' => $subquery,
            'alias' => $alias
        ];
        $this->bindings = array_merge($this->bindings, $subquery->getBindings());
        return $this;
    }

    /**
     * Add a JOIN clause
     *
     * @param string|QueryBuilder $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @param JoinType $type
     * @param string|null $alias
     */
    public function join(
        string|QueryBuilder $table,
        string $first,
        string $operator,
        string $second,
        JoinType $type = JoinType::INNER,
        ?string $alias = null
    ): self {
        $join = [
            'type' => $type,
            'first' => $first,
            'operator' => $operator,
            'second' => $second,
            'alias' => $alias
        ];

        if ($table instanceof QueryBuilder) {
            $join['subquery'] = $table;
            $this->bindings = array_merge($this->bindings, $table->getBindings());
        } else {
            $join['table'] = $table;
        }

        $this->joins[] = $join;
        return $this;
    }

    /**
     * Add an INNER JOIN
     */
    public function innerJoin(string|QueryBuilder $table, string $first, string $operator, string $second, ?string $alias = null): self
    {
        return $this->join($table, $first, $operator, $second, JoinType::INNER, $alias);
    }

    /**
     * Add a LEFT JOIN
     */
    public function leftJoin(string|QueryBuilder $table, string $first, string $operator, string $second, ?string $alias = null): self
    {
        return $this->join($table, $first, $operator, $second, JoinType::LEFT, $alias);
    }

    /**
     * Add a RIGHT JOIN
     */
    public function rightJoin(string|QueryBuilder $table, string $first, string $operator, string $second, ?string $alias = null): self
    {
        return $this->join($table, $first, $operator, $second, JoinType::RIGHT, $alias);
    }

    /**
     * Add a CROSS JOIN
     */
    public function crossJoin(string $table, ?string $alias = null): self
    {
        $this->joins[] = [
            'type' => JoinType::CROSS,
            'table' => $table,
            'alias' => $alias
        ];
        return $this;
    }

    /**
     * Add a WHERE condition with parameter binding
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @param string $boolean
     */
    public function where(string $column, string $operator, mixed $value, string $boolean = 'AND'): self
    {
        $paramName = $this->generateBindingName();
        $this->bindings[$paramName] = $value;

        $this->where[] = [
            'column' => $column,
            'operator' => $operator,
            'param' => $paramName,
            'boolean' => $boolean
        ];

        return $this;
    }

    /**
     * Add an OR WHERE condition
     */
    public function orWhere(string $column, string $operator, mixed $value): self
    {
        return $this->where($column, $operator, $value, 'OR');
    }

    /**
     * Add a raw WHERE clause (use with caution)
     */
    public function whereRaw(string $sql, string $boolean = 'AND'): self
    {
        $this->where[] = [
            'raw' => $sql,
            'boolean' => $boolean
        ];
        return $this;
    }

    /**
     * Add a WHERE IN condition
     *
     * @param string $column
     * @param array<mixed> $values
     * @param string $boolean
     */
    public function whereIn(string $column, array $values, string $boolean = 'AND'): self
    {
        $params = [];
        foreach ($values as $value) {
            $paramName = $this->generateBindingName();
            $this->bindings[$paramName] = $value;
            $params[] = $paramName;
        }

        $this->where[] = [
            'column' => $column,
            'in' => $params,
            'boolean' => $boolean
        ];

        return $this;
    }

    /**
     * Add a WHERE BETWEEN condition
     */
    public function whereBetween(string $column, mixed $min, mixed $max, string $boolean = 'AND'): self
    {
        $paramMin = $this->generateBindingName();
        $paramMax = $this->generateBindingName();
        $this->bindings[$paramMin] = $min;
        $this->bindings[$paramMax] = $max;

        $this->where[] = [
            'column' => $column,
            'between' => [$paramMin, $paramMax],
            'boolean' => $boolean
        ];

        return $this;
    }

    /**
     * Add a GROUP BY clause
     */
    public function groupBy(string ...$columns): self
    {
        $this->groupBy = array_merge($this->groupBy, $columns);
        return $this;
    }

    /**
     * Add a HAVING clause
     */
    public function having(string $column, string $operator, mixed $value, string $boolean = 'AND'): self
    {
        $paramName = $this->generateBindingName();
        $this->bindings[$paramName] = $value;

        $this->having[] = [
            'column' => $column,
            'operator' => $operator,
            'param' => $paramName,
            'boolean' => $boolean
        ];

        return $this;
    }

    /**
     * Add an ORDER BY clause
     */
    public function orderBy(string $column, OrderDirection $direction = OrderDirection::ASC): self
    {
        $this->orderBy[] = [
            'column' => $column,
            'direction' => $direction
        ];
        return $this;
    }

    /**
     * Set the LIMIT clause
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set the OFFSET clause
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Get the generated SQL query
     */
    public function getSQL(): string
    {
        $sql = 'SELECT ';

        // SELECT clause
        $sql .= empty($this->select) ? '*' : implode(', ', $this->select);

        // FROM clause
        if (!empty($this->from)) {
            $sql .= ' FROM ';
            if (isset($this->from['subquery'])) {
                $sql .= '(' . $this->from['subquery']->getSQL() . ')';
                if ($this->from['alias']) {
                    $sql .= ' AS ' . $this->from['alias'];
                }
            } else {
                $sql .= $this->from['table'];
                if ($this->from['alias']) {
                    $sql .= ' AS ' . $this->from['alias'];
                }
            }
        }

        // JOIN clauses
        foreach ($this->joins as $join) {
            if (isset($join['type'])) {
                $sql .= ' ' . $join['type']->value . ' ';
            }

            if (isset($join['subquery'])) {
                $sql .= '(' . $join['subquery']->getSQL() . ')';
                if ($join['alias']) {
                    $sql .= ' AS ' . $join['alias'];
                }
            } else {
                $sql .= $join['table'];
                if ($join['alias']) {
                    $sql .= ' AS ' . $join['alias'];
                }
            }

            // ON condition (not for CROSS JOIN)
            if (isset($join['first'])) {
                $sql .= ' ON ' . $join['first'] . ' ' . $join['operator'] . ' ' . $join['second'];
            }
        }

        // WHERE clause
        if (!empty($this->where)) {
            $sql .= ' WHERE ' . $this->buildWhereClause();
        }

        // GROUP BY clause
        if (!empty($this->groupBy)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groupBy);
        }

        // HAVING clause
        if (!empty($this->having)) {
            $sql .= ' HAVING ' . $this->buildHavingClause();
        }

        // ORDER BY clause
        if (!empty($this->orderBy)) {
            $sql .= ' ORDER BY ' . implode(', ', array_map(
                fn($order) => $order['column'] . ' ' . $order['direction']->value,
                $this->orderBy
            ));
        }

        // LIMIT clause
        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        // OFFSET clause
        if ($this->offset !== null) {
            $sql .= ' OFFSET ' . $this->offset;
        }

        return trim($sql);
    }

    /**
     * Get formatted SQL with syntax highlighting
     */
    public function getFormattedSQL(bool $highlight = true): string
    {
        $formatter = $this->formatter ?? new SqlFormatter();
        return $formatter->format($this->getSQL(), $highlight);
    }

    /**
     * Get parameter bindings
     *
     * @return array<string, mixed>
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * Reset the builder
     */
    public function reset(): static
    {
        $this->select = [];
        $this->from = [];
        $this->joins = [];
        $this->where = [];
        $this->groupBy = [];
        $this->having = [];
        $this->orderBy = [];
        $this->limit = null;
        $this->offset = null;
        $this->bindings = [];
        $this->bindingCounter = 0;

        return $this;
    }

    /**
     * Build WHERE clause string
     */
    private function buildWhereClause(): string
    {
        $clauses = [];

        foreach ($this->where as $index => $condition) {
            $clause = '';

            // Add boolean operator (except for first condition)
            if ($index > 0) {
                $clause .= ' ' . $condition['boolean'] . ' ';
            }

            // Handle raw SQL
            if (isset($condition['raw'])) {
                $clause .= $condition['raw'];
            }
            // Handle IN clause
            elseif (isset($condition['in'])) {
                $params = implode(', ', array_map(fn($p) => ':' . $p, $condition['in']));
                $clause .= $condition['column'] . ' IN (' . $params . ')';
            }
            // Handle BETWEEN clause
            elseif (isset($condition['between'])) {
                $clause .= $condition['column'] . ' BETWEEN :' . $condition['between'][0] . ' AND :' . $condition['between'][1];
            }
            // Handle standard condition
            else {
                $clause .= $condition['column'] . ' ' . $condition['operator'] . ' :' . $condition['param'];
            }

            $clauses[] = $clause;
        }

        return implode('', $clauses);
    }

    /**
     * Build HAVING clause string
     */
    private function buildHavingClause(): string
    {
        $clauses = [];

        foreach ($this->having as $index => $condition) {
            $clause = '';

            if ($index > 0) {
                $clause .= ' ' . $condition['boolean'] . ' ';
            }

            $clause .= $condition['column'] . ' ' . $condition['operator'] . ' :' . $condition['param'];
            $clauses[] = $clause;
        }

        return implode('', $clauses);
    }

    /**
     * Generate a unique binding parameter name
     */
    private function generateBindingName(): string
    {
        return 'param_' . $this->bindingCounter++;
    }
}
