<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class DropBuilder implements BuilderInterface
{
    private string $type = 'TABLE'; // TABLE, INDEX, DATABASE
    private string $name = '';
    private bool $ifExists = false;
    private ?string $table = null; // For DROP INDEX

    public function __construct(
        private readonly ?SqlFormatter $formatter = null
    ) {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * Drop a table
     */
    public function table(string $name): self
    {
        $this->type = 'TABLE';
        $this->name = $name;
        return $this;
    }

    /**
     * Drop an index
     */
    public function index(string $name, string $table): self
    {
        $this->type = 'INDEX';
        $this->name = $name;
        $this->table = $table;
        return $this;
    }

    /**
     * Drop a database
     */
    public function database(string $name): self
    {
        $this->type = 'DATABASE';
        $this->name = $name;
        return $this;
    }

    /**
     * Add IF EXISTS clause
     */
    public function ifExists(): self
    {
        $this->ifExists = true;
        return $this;
    }

    public function getSQL(): string
    {
        $sql = 'DROP ' . $this->type . ' ';

        if ($this->ifExists) {
            $sql .= 'IF EXISTS ';
        }

        $sql .= $this->name;

        if ($this->type === 'INDEX' && $this->table) {
            $sql .= ' ON ' . $this->table;
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
        $this->type = 'TABLE';
        $this->name = '';
        $this->ifExists = false;
        $this->table = null;

        return $this;
    }
}
