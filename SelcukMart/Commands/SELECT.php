<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:29
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;
use SelcukMart\SQLOperations\SQLBuilderHook;

class SELECT extends AbstractCommands implements CommandsInterface
{
    
    public function build(array $options)
    {
        $this->output = 'SELECT ';
        $this->hook_core_key = 'SELECT';
        $this->setOutput($this->output);
        $this->i = 0;
        $this->total = _sizeof($options);

        $this->SQLBuilderHook = new SQLBuilderHook($this->SQLBuilder->getId());

        $this->position = 'APPEND';
        $this->output = $this->hookGet();
        if (!empty($this->output) && $this->total > 0) {
            $this->output .= ', ';
        }
        $this->setOutput($this->output);

        foreach ($options as $index => $option) {
            $this->buildCore($index, $option);
            if ($this->total !== $this->i) {
                $this->output .= ', ';
            }
            $this->output .= 'company_id,';
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



    
}