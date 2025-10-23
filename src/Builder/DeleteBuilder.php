<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class DeleteBuilder implements BuilderInterface
{
    private string $table = '';
    private array $where = [];
    private ?int $limit = null;
    private array $bindings = [];
    private int $bindingCounter = 0;

    public function __construct(
        private readonly ?SqlFormatter $formatter = null
    ) {
    }

    /**
     * Create a new DeleteBuilder instance
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Set the table to delete from
     */
    public function from(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Add a WHERE condition
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
     * Add a raw WHERE clause
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
     * Set the LIMIT clause
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSQL(): string
    {
        $sql = 'DELETE FROM ' . $this->table;

        // WHERE clause
        if (!empty($this->where)) {
            $sql .= ' WHERE ' . $this->buildWhereClause();
        }

        // LIMIT clause
        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        return $sql;
    }

    /**
     * {@inheritDoc}
     */
    public function getFormattedSQL(bool $highlight = true): string
    {
        $formatter = $this->formatter ?? new SqlFormatter();
        return $formatter->format($this->getSQL(), $highlight);
    }

    /**
     * {@inheritDoc}
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * {@inheritDoc}
     */
    public function reset(): static
    {
        $this->table = '';
        $this->where = [];
        $this->limit = null;
        $this->bindings = [];
        $this->bindingCounter = 0;

        return $this;
    }

    private function buildWhereClause(): string
    {
        $clauses = [];

        foreach ($this->where as $index => $condition) {
            $clause = '';

            if ($index > 0) {
                $clause .= ' ' . $condition['boolean'] . ' ';
            }

            if (isset($condition['raw'])) {
                $clause .= $condition['raw'];
            } else {
                $clause .= $condition['column'] . ' ' . $condition['operator'] . ' :' . $condition['param'];
            }

            $clauses[] = $clause;
        }

        return implode('', $clauses);
    }

    private function generateBindingName(): string
    {
        return 'param_' . $this->bindingCounter++;
    }
}
