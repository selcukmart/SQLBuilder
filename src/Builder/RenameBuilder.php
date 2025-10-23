<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class RenameBuilder implements BuilderInterface
{
    private array $renames = [];

    public function __construct(
        private readonly ?SqlFormatter $formatter = null
    ) {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * Rename a table
     */
    public function table(string $oldName, string $newName): self
    {
        $this->renames[] = [
            'old' => $oldName,
            'new' => $newName
        ];
        return $this;
    }

    public function getSQL(): string
    {
        if (count($this->renames) === 1) {
            $rename = $this->renames[0];
            return 'RENAME TABLE ' . $rename['old'] . ' TO ' . $rename['new'];
        }

        // Multiple renames
        $parts = [];
        foreach ($this->renames as $rename) {
            $parts[] = $rename['old'] . ' TO ' . $rename['new'];
        }

        return 'RENAME TABLE ' . implode(', ', $parts);
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
        $this->renames = [];
        return $this;
    }
}
