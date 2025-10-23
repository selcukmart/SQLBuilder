<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class TruncateBuilder implements BuilderInterface
{
    private string $table = '';

    public function __construct(
        private readonly ?SqlFormatter $formatter = null
    ) {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * Set the table to truncate
     */
    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function getSQL(): string
    {
        return 'TRUNCATE TABLE ' . $this->table;
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
        $this->table = '';
        return $this;
    }
}
