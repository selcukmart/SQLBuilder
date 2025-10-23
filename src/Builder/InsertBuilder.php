<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class InsertBuilder implements BuilderInterface
{
    private string $table = '';
    private array $columns = [];
    private array $values = [];
    private array $bindings = [];
    private int $bindingCounter = 0;

    public function __construct(
        private readonly ?SqlFormatter $formatter = null
    ) {
    }

    /**
     * Create a new InsertBuilder instance
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Set the table to insert into
     */
    public function into(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set columns and values to insert
     *
     * @param array<string, mixed> $data
     */
    public function values(array $data): self
    {
        $this->columns = array_keys($data);

        foreach ($data as $value) {
            $paramName = $this->generateBindingName();
            $this->bindings[$paramName] = $value;
            $this->values[] = $paramName;
        }

        return $this;
    }

    /**
     * Insert multiple rows
     *
     * @param array<array<string, mixed>> $rows
     */
    public function multipleValues(array $rows): self
    {
        if (empty($rows)) {
            return $this;
        }

        $this->columns = array_keys($rows[0]);
        $this->values = [];

        foreach ($rows as $row) {
            $rowParams = [];
            foreach ($row as $value) {
                $paramName = $this->generateBindingName();
                $this->bindings[$paramName] = $value;
                $rowParams[] = $paramName;
            }
            $this->values[] = $rowParams;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSQL(): string
    {
        $sql = 'INSERT INTO ' . $this->table;

        // Columns
        if (!empty($this->columns)) {
            $sql .= ' (' . implode(', ', $this->columns) . ')';
        }

        // Values
        $sql .= ' VALUES ';

        if (is_array($this->values[0] ?? null)) {
            // Multiple rows
            $rows = [];
            foreach ($this->values as $row) {
                $rows[] = '(' . implode(', ', array_map(fn($p) => ':' . $p, $row)) . ')';
            }
            $sql .= implode(', ', $rows);
        } else {
            // Single row
            $sql .= '(' . implode(', ', array_map(fn($p) => ':' . $p, $this->values)) . ')';
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
        $this->columns = [];
        $this->values = [];
        $this->bindings = [];
        $this->bindingCounter = 0;

        return $this;
    }

    private function generateBindingName(): string
    {
        return 'param_' . $this->bindingCounter++;
    }
}
