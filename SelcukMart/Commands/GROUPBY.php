<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:45
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;
use SelcukMart\SQLOperations\SQLBuilderHook;

class GROUPBY implements CommandsInterface
{
    use CommandsTrait;

    private $SQLBuilder;

    public function __construct(SQLBuilder $SQLBuilder)
    {
        $this->SQLBuilder = $SQLBuilder;
    }

    public function build(array $options)
    {
        $this->output = ' GROUP BY ';
        $this->hook_core_key = 'GROUPBY';
        $this->setOutput($this->output);
        $this->i = 0;
        $this->total = _sizeof($options);
        $this->SQLBuilderHook = new SQLBuilderHook($this->SQLBuilder->getId());
        $this->position = 'APPEND';
        $output = $this->hookGet();
        if (!empty($output)) {
            if ($this->total > 0) {
                $output .= ', ';
            }
        }
        $this->setOutput($output);

        foreach ($options as $index => $option) {
            $this->output = '';
            $this->i++;

            if (is_numeric($index) && is_array($option)) {
                $this->SQLBuilder->build($option);
            } else {
                if (is_string($index) || is_array($option)) {
                    $this->output .= $this->concateTheCommas($index, $option);
                } else {
                    $this->output .= $option;
                }
            }

            if ($this->total > $this->i) {
                $this->output .= ', ';
            }

            $this->setOutput($this->output);
        }

        $this->position = 'PREPEND';
        $output = $this->hookGet();
        if (!empty($output)) {
            if ($this->total > 0) {
                $output = ', ' . $output;
            }
        }
        $this->setOutput($output);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}