<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Builder;

use SelcukMart\SQLBuilder\Contracts\BuilderInterface;
use Doctrine\SqlFormatter\SqlFormatter;

class CreateTableBuilder implements BuilderInterface
{
    private string $table = '';
    private array $columns = [];
    private array $primaryKey = [];
    private array $indexes = [];
    private array $foreignKeys = [];
    private array $options = [];
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
     * Set the table name
     */
    public function table(string $table): self
    {
        $this->table = $table;
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

    /**
     * Add a column
     *
     * @param string $name Column name
     * @param string $type Column type (e.g., 'INT', 'VARCHAR(255)')
     * @param array<string> $modifiers Column modifiers (e.g., ['NOT NULL', 'AUTO_INCREMENT'])
     */
    public function column(string $name, string $type, array $modifiers = []): self
    {
        $this->columns[] = [
            'name' => $name,
            'type' => $type,
            'modifiers' => $modifiers
        ];
        return $this;
    }

    /**
     * Add an integer column
     */
    public function integer(string $name, array $modifiers = []): self
    {
        return $this->column($name, 'INT', $modifiers);
    }

    /**
     * Add a varchar column
     */
    public function varchar(string $name, int $length = 255, array $modifiers = []): self
    {
        return $this->column($name, "VARCHAR($length)", $modifiers);
    }

    /**
     * Add a text column
     */
    public function text(string $name, array $modifiers = []): self
    {
        return $this->column($name, 'TEXT', $modifiers);
    }

    /**
     * Add a timestamp column
     */
    public function timestamp(string $name, array $modifiers = []): self
    {
        return $this->column($name, 'TIMESTAMP', $modifiers);
    }

    /**
     * Add timestamps (created_at, updated_at)
     */
    public function timestamps(): self
    {
        return $this->timestamp('created_at', ['DEFAULT CURRENT_TIMESTAMP'])
            ->timestamp('updated_at', ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
    }

    /**
     * Set primary key
     */
    public function primaryKey(string ...$columns): self
    {
        $this->primaryKey = $columns;
        return $this;
    }

    /**
     * Add an index
     */
    public function index(string $name, string ...$columns): self
    {
        $this->indexes[] = [
            'name' => $name,
            'columns' => $columns
        ];
        return $this;
    }

    /**
     * Add a unique index
     */
    public function unique(string $name, string ...$columns): self
    {
        $this->indexes[] = [
            'name' => $name,
            'columns' => $columns,
            'unique' => true
        ];
        return $this;
    }

    /**
     * Add a foreign key
     */
    public function foreignKey(string $column, string $refTable, string $refColumn, ?string $onDelete = null, ?string $onUpdate = null): self
    {
        $this->foreignKeys[] = [
            'column' => $column,
            'ref_table' => $refTable,
            'ref_column' => $refColumn,
            'on_delete' => $onDelete,
            'on_update' => $onUpdate
        ];
        return $this;
    }

    /**
     * Set table options (ENGINE, CHARSET, etc.)
     */
    public function options(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Set engine
     */
    public function engine(string $engine): self
    {
        $this->options['ENGINE'] = $engine;
        return $this;
    }

    /**
     * Set charset
     */
    public function charset(string $charset): self
    {
        $this->options['DEFAULT CHARSET'] = $charset;
        return $this;
    }

    public function getSQL(): string
    {
        $sql = 'CREATE TABLE ';

        if ($this->ifNotExists) {
            $sql .= 'IF NOT EXISTS ';
        }

        $sql .= $this->table . ' (';

        $parts = [];

        // Columns
        foreach ($this->columns as $column) {
            $columnDef = $column['name'] . ' ' . $column['type'];
            if (!empty($column['modifiers'])) {
                $columnDef .= ' ' . implode(' ', $column['modifiers']);
            }
            $parts[] = $columnDef;
        }

        // Primary key
        if (!empty($this->primaryKey)) {
            $parts[] = 'PRIMARY KEY (' . implode(', ', $this->primaryKey) . ')';
        }

        // Indexes
        foreach ($this->indexes as $index) {
            $indexDef = isset($index['unique']) ? 'UNIQUE INDEX ' : 'INDEX ';
            $indexDef .= $index['name'] . ' (' . implode(', ', $index['columns']) . ')';
            $parts[] = $indexDef;
        }

        // Foreign keys
        foreach ($this->foreignKeys as $fk) {
            $fkDef = 'FOREIGN KEY (' . $fk['column'] . ') REFERENCES ' .
                     $fk['ref_table'] . '(' . $fk['ref_column'] . ')';

            if ($fk['on_delete']) {
                $fkDef .= ' ON DELETE ' . $fk['on_delete'];
            }
            if ($fk['on_update']) {
                $fkDef .= ' ON UPDATE ' . $fk['on_update'];
            }

            $parts[] = $fkDef;
        }

        $sql .= implode(', ', $parts) . ')';

        // Options
        if (!empty($this->options)) {
            foreach ($this->options as $key => $value) {
                $sql .= ' ' . $key . '=' . $value;
            }
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
        $this->table = '';
        $this->columns = [];
        $this->primaryKey = [];
        $this->indexes = [];
        $this->foreignKeys = [];
        $this->options = [];
        $this->ifNotExists = false;

        return $this;
    }
}
