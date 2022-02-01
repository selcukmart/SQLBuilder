<?php
/**
 * @author selcukmart
 * 31.01.2022
 * 15:12
 */

namespace SelcukMart\Commands;

use SelcukMart\SQLBuilder;

abstract class AbstractCommands
{

    use CommandsTrait;

    protected
        $SQLBuilder,
        $i,
        $hook_core_key,
        $output,
        $SQLBuilderHook,
        $position,
        $total;


    public function __construct(SQLBuilder $SQLBuilder)
    {
        $this->SQLBuilder = $SQLBuilder;
    }
    
    /**
     * @param $index
     * @param $option
     * @author selcukmart
     * 31.01.2022
     * 15:13
     */
    protected function buildCore($index, $option): void
    {
        $this->i++;
        $this->output = '';

        if (is_numeric($index) && is_array($option)) {
            $this->SQLBuilder->build($option);
        } elseif (is_string($index) || is_array($option)) {
            $this->output = $this->concateTheCommas($index, $option);
        } else {
            $this->output = $option;
        }


    }

    public function __destruct()
    {

    }
}