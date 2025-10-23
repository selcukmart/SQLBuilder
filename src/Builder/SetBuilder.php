<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class SetBuilder implements BuilderInterface
{
    private array $variables = [];

    public function __construct(
        private readonly ?SqlFormatter $formatter = null
    ) {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * Set a variable
     */
    public function variable(string $name, mixed $value): self
    {
        $this->variables[$name] = $value;
        return $this;
    }

    /**
     * Set multiple variables
     */
    public function variables(array $variables): self
    {
        $this->variables = array_merge($this->variables, $variables);
        return $this;
    }

    public function getSQL(): string
    {
        $parts = [];

        foreach ($this->variables as $name => $value) {
            if (is_string($value)) {
                $value = "'" . addslashes($value) . "'";
            } elseif (is_bool($value)) {
                $value = $value ? '1' : '0';
            } elseif (is_null($value)) {
                $value = 'NULL';
            }

            $parts[] = $name . ' = ' . $value;
        }

        return 'SET ' . implode(', ', $parts);
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
        $this->variables = [];
        return $this;
    }
}
