<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:30
 */

namespace SelcukMart;

use SqlFormatter;
use SelcukMart\Tools\SQLVariablesTrait;
use SelcukMart\SQLOperations\SQLBuilderHook;

class SQLBuilder
{
    use SQLVariablesTrait;

    private
        $statement,
        $output = '',
        $depth = 0,
        $id,
        $after_close_output = '',
        $hook_positions = [
        'APPEND',
        'PREPEND'
    ],
        $hook_keys = [
        'JOIN',
        'FROM',
        'WHERE',
        'SELECT',
        'GROUPBY',
        'ORDERBY',
        'LIMIT',
        'DELETE',
        'UPDATE',
        'INSERT'
    ],
        $hook_commands2keys = [
        'FROM' => 'FROM',
        'JOIN' => 'JOIN',
        'INNERJOIN' => 'JOIN',
        'LEFTJOIN' => 'JOIN',
        'OUTERJOIN' => 'JOIN',
        'FULLOUTERJOIN' => 'JOIN',
        'RIGHTJOIN' => 'JOIN',
        'CROSSJOIN' => 'JOIN',
        'WHERE' => 'WHERE',
        'SELECT' => 'SELECT',
        'GROUPBY' => 'GROUPBY',
        'ORDERBY' => 'ORDERBY',
        'LIMIT' => 'LIMIT',
        'DELETE' => 'DELETE',
        'UPDATE' => 'UPDATE',
        'INSERT' => 'INSERT'
    ],
        $statements = [],
        $has_where = false;

    public $namespace;

    public function __construct()
    {
        if (isset($this->statement['id'])) {
            $this->id = $this->statement['id'];
            unset($this->statement['id']);
        } else {
            global $sayfa;
            if (is_object($sayfa)) {
                $this->id = $sayfa->id;
            } else {
                $this->id = rand(0, 999);
            }
        }
    }

    public function build($statement = null)
    {
        /**
         * if this is null, you must set using setStatement
         */
        if (!is_null($statement)) {
            $this->statement = $statement;
        }

        $this->namespace = __NAMESPACE__;
        $SQLBuilderHook = new SQLBuilderHook($this->id);

        $this->increaseDepth();
        $this->statement = $this->statements[$this->depth];
        if ($this->depth > 1) {
            $this->setOutput('(');
        }

        $key = 'JOIN';
        $position = 'PREPEND';
        $prepend_key = SQLBuilderHook::key($key, $position, $this->id);
        $position = 'APPEND';
        $append_key = SQLBuilderHook::key($key, $position, $this->id);

        foreach ($this->statement as $index => $options) {

            $command = $options['type'];
            unset($options['type']);

            $command = $this->commandPrepare($command);
            $is_join = $this->isJoin($command);

            /**
             * Append Hook
             */
            if ($this->depth == 1 && $is_join) {
                $this->setOutput($SQLBuilderHook->get($append_key));
            }
            $class = $this->namespace . '\Commands\\' . $command;
            $command_obje = new $class($this);
            if (is_string($options)) {
                $options = [$options];
            }
            $command_obje->build($options);

            /**
             * Prepend Hook
             */
            if ($this->depth == 1 && $is_join) {
                $next = $index + 1;
                if (isset($this->statement[$next])) {
                    $next_arr = $this->statement[$next];
                    if (!$this->isJoin($next_arr['type'])) {
                        $this->setOutput($SQLBuilderHook->get($prepend_key));
                    }
                }
            }
        }

        if ($this->depth > 1) {
            $this->setOutput(')');
        }

        $this->decreaseDepth();
        $this->setOutput($this->after_close_output);
        $this->after_close_output = '';
        return $this->getOutput();
    }

    private function isJoin($type)
    {
        return false !== strpos($type, "JOIN");
    }

    /**
     * @param string $output
     */
    public function setOutput(string $output): void
    {

        $this->output .= $output;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }

    public function getOutputFormatted($highlight = true): string
    {
        //return SqlFormatter::highlight(SqlFormatter::format($this->output));
        return SqlFormatter::format($this->output, $highlight);
    }

    public function getOutputHighlighted(): string
    {
        return $this->getOutputFormatted(true);
    }


    /**
     * @param int $depth
     */
    public function increaseDepth(): void
    {
        $this->depth++;
        $this->statements[$this->depth] = $this->statement;
    }

    public function decreaseDepth(): void
    {
        $this->depth--;
        if (isset($this->statements[$this->depth])) {
            $this->statement = $this->statements[$this->depth];
        }

    }

    /**
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @return mixed|string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param int $depth
     */
    public function setDepth(int $depth): void
    {
        $this->depth = $depth;
    }

    public function setDestructOutput($output)
    {
        $this->after_close_output .= $output;
    }

    /**
     * @param false|int|mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $statement
     */
    public function setStatement($statement): void
    {
        $this->statement = $statement;
    }

    /**
     * @param string $after_close_output
     */
    public function setAfterCloseOutput(string $after_close_output): void
    {
        $this->after_close_output = $after_close_output;
    }

    /**
     * @return mixed
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * @param bool $has_where
     */
    public function setHasWhere(bool $has_where): void
    {
        $this->has_where = $has_where;
    }

    /**
     * @return bool
     */
    public function isHasWhere(): bool
    {
        return $this->has_where;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}