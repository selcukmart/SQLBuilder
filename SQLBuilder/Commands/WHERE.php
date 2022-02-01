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

class WHERE extends AbstractCommands implements CommandsInterface
{
    
    use SQLToolsTrait;

    public function build(array $options)
    {

        $this->output = ' WHERE ';
        $this->setOutput($this->output);
        $this->hook_core_key = 'WHERE';
        $this->i = 0;
        $this->total = _sizeof($options);
        if ($this->total > 0 && $this->SQLBuilder->getDepth() === 1) {
            $this->SQLBuilder->setHasWhere(true);
        }
        $this->SQLBuilderHook = new SQLBuilderHook($this->SQLBuilder->getId());

        $this->position = 'APPEND';
        $output = $this->hookGet();
        $this->setOutput($output);

        if(isset($options['WHERE'])){
            $options = [$options['WHERE']];
        }
        foreach ($options as $index => $option) {
            $this->output = '';
            $this->i++;

            if (is_array($option)) {
                $this->SQLBuilder->build($option);
            } else {
                $this->table_as = '';
                if (is_string($index)) {
                    $this->table_as = $index;
                }
                $option_arr = [$index => $option];
                $this->where($option_arr);
            }
        }

        $this->position = 'PREPEND';
        $output = $this->hookGet();
        $this->setOutput($output);
    }

    
}