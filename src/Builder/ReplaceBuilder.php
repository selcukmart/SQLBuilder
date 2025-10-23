<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class ReplaceBuilder implements BuilderInterface
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
     * Create a new ReplaceBuilder instance
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Set the table to replace into
     */
    public function into(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set columns and values to replace
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
     * Replace multiple rows
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

    public function getSQL(): string
    {
        $sql = 'REPLACE INTO ' . $this->table;

        if (!empty($this->columns)) {
            $sql .= ' (' . implode(', ', $this->columns) . ')';
        }

        $sql .= ' VALUES ';

        if (is_array($this->values[0] ?? null)) {
            $rows = [];
            foreach ($this->values as $row) {
                $rows[] = '(' . implode(', ', array_map(fn($p) => ':' . $p, $row)) . ')';
            }
            $sql .= implode(', ', $rows);
        } else {
            $sql .= '(' . implode(', ', array_map(fn($p) => ':' . $p, $this->values)) . ')';
        }

        return $sql;
    }

    public function getFormattedSQL(bool $highlight = true): string
    {
        $formatter = $this->formatter ?? new SqlFormatter();
        return $formatter->format($this->getSQL(), $highlight);
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

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
