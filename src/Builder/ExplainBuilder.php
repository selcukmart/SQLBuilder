<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class ExplainBuilder implements BuilderInterface
{
    private ?QueryBuilder $query = null;
    private ?string $rawQuery = null;
    private string $format = ''; // TRADITIONAL, JSON, TREE

    public function __construct(
        private readonly ?SqlFormatter $formatter = null
    ) {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * Set the query to explain
     */
    public function query(QueryBuilder $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Set raw query to explain
     */
    public function rawQuery(string $query): self
    {
        $this->rawQuery = $query;
        return $this;
    }

    /**
     * Set output format
     */
    public function format(string $format): self
    {
        $this->format = strtoupper($format);
        return $this;
    }

    public function getSQL(): string
    {
        $sql = 'EXPLAIN';

        if ($this->format) {
            $sql .= ' FORMAT=' . $this->format;
        }

        $sql .= ' ';

        if ($this->query) {
            $sql .= $this->query->getSQL();
        } elseif ($this->rawQuery) {
            $sql .= $this->rawQuery;
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
        return $this->query ? $this->query->getBindings() : [];
    }

    public function reset(): static
    {
        $this->query = null;
        $this->rawQuery = null;
        $this->format = '';

        return $this;
    }
}
