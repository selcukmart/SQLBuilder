<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Contracts;

interface BuilderInterface
{
    /**
     * Get the generated SQL query string
     */
    public function getSQL(): string;

    /**
     * Get the formatted SQL query string with syntax highlighting
     */
    public function getFormattedSQL(bool $highlight = true): string;

    /**
     * Get the parameter bindings for prepared statements
     *
     * @return array<string, mixed>
     */
    public function getBindings(): array;

    /**
     * Reset the builder to its initial state
     */
    public function reset(): static;
}
