<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:29
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;
use SelcukMart\Tools\SQLToolsTrait;
use SelcukMart\SQLOperations\SQLBuilderHook;

class UPDATE implements CommandsInterface
{
    use CommandsTrait;
    use SQLToolsTrait;

    private $SQLBuilder;

    public function __construct(SQLBuilder $SQLBuilder)
    {
        $this->SQLBuilder = $SQLBuilder;
    }

    public function build(array $options)
    {
        $this->output = 'UPDATE ';
        $this->setOutput($this->output);
        $this->hook_core_key = 'UPDATE';
        $this->i = 0;
        $this->total = _sizeof($options);
        $this->SQLBuilderHook = new SQLBuilderHook($this->SQLBuilder->getId());

        $this->position = 'APPEND';
        $output = $this->hookGet();
        if (!empty($output)) {
            $this->output .= $output;
            if ($this->total > 0) {
                $this->output .= ', ';
            }
        }
        $this->setOutput($this->output);

        foreach ($options as $index => $option) {
            $this->i++;
            $this->output = '';

            $operation = $index;
            if (method_exists($this, $operation)) {
                $this->$operation($option);
            } else {
                if (is_numeric($index) && is_array($option)) {
                    $this->SQLBuilder->build($option);
                } else {
                    if (is_string($index) || is_array($option)) {
                        $this->output = $this->concateTheCommas($index, $option);
                    } else {
                        $this->output = $option;
                    }
                }
                if (!empty($this->output)) {
                    if ($this->total > $this->i) {
                        $this->output .= ', ';
                    }
                    $this->setOutput($this->output);
                }
            }
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