<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class CreateIndexBuilder implements BuilderInterface
{
    private string $indexName = '';
    private string $table = '';
    private array $columns = [];
    private bool $unique = false;
    private bool $ifNotExists = false;

    public function __construct(
        private readonly ?SqlFormatter $formatter = null
    ) {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * Set index name
     */
    public function name(string $name): self
    {
        $this->indexName = $name;
        return $this;
    }

    /**
     * Set table name
     */
    public function on(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set columns for index
     */
    public function columns(string ...$columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Make index unique
     */
    public function unique(): self
    {
        $this->unique = true;
        return $this;
    }

    /**
     * Add IF NOT EXISTS clause
     */
    public function ifNotExists(): self
    {
        $this->ifNotExists = true;
        return $this;
    }

    public function getSQL(): string
    {
        $sql = 'CREATE ';

        if ($this->unique) {
            $sql .= 'UNIQUE ';
        }

        $sql .= 'INDEX ';

        if ($this->ifNotExists) {
            $sql .= 'IF NOT EXISTS ';
        }

        $sql .= $this->indexName . ' ON ' . $this->table . ' (' . implode(', ', $this->columns) . ')';

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
        $this->indexName = '';
        $this->table = '';
        $this->columns = [];
        $this->unique = false;
        $this->ifNotExists = false;

        return $this;
    }
}
