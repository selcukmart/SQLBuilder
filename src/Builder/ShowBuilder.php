<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class ShowBuilder implements BuilderInterface
{
    private string $type = ''; // TABLES, DATABASES, COLUMNS, INDEX, etc.
    private ?string $target = null;
    private ?string $like = null;
    private ?string $where = null;

    public function __construct(
        private readonly ?SqlFormatter $formatter = null
    ) {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * Show tables
     */
    public function tables(?string $database = null): self
    {
        $this->type = 'TABLES';
        $this->target = $database;
        return $this;
    }

    /**
     * Show databases
     */
    public function databases(): self
    {
        $this->type = 'DATABASES';
        return $this;
    }

    /**
     * Show columns from table
     */
    public function columns(string $table): self
    {
        $this->type = 'COLUMNS';
        $this->target = $table;
        return $this;
    }

    /**
     * Show indexes from table
     */
    public function indexes(string $table): self
    {
        $this->type = 'INDEX';
        $this->target = $table;
        return $this;
    }

    /**
     * Show create table
     */
    public function createTable(string $table): self
    {
        $this->type = 'CREATE TABLE';
        $this->target = $table;
        return $this;
    }

    /**
     * Add LIKE clause
     */
    public function like(string $pattern): self
    {
        $this->like = $pattern;
        return $this;
    }

    /**
     * Add WHERE clause
     */
    public function where(string $condition): self
    {
        $this->where = $condition;
        return $this;
    }

    public function getSQL(): string
    {
        $sql = 'SHOW ' . $this->type;

        if ($this->target) {
            if ($this->type === 'TABLES' || $this->type === 'COLUMNS' || $this->type === 'INDEX') {
                $sql .= ' FROM ' . $this->target;
            } else {
                $sql .= ' ' . $this->target;
            }
        }

        if ($this->like) {
            $sql .= " LIKE '" . $this->like . "'";
        }

        if ($this->where) {
            $sql .= ' WHERE ' . $this->where;
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
        return [];
    }

    public function reset(): static
    {
        $this->type = '';
        $this->target = null;
        $this->like = null;
        $this->where = null;

        return $this;
    }
}
