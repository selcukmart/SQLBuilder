<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:29
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;
use SelcukMart\SQLOperations\SQLBuilderHook;

class DELETE extends AbstractCommands implements CommandsInterface
{

    public function build(array $options)
    {
        $this->output = 'DELETE ';
        $this->setOutput($this->output);
        $this->hook_core_key = 'DELETE';
        $this->i = 0;
        $this->total = _sizeof($options);
        $this->SQLBuilderHook = new SQLBuilderHook($this->SQLBuilder->getId());

        $this->position = 'APPEND';
        $output = $this->hookGet();
        $this->setOutput($output);

        foreach ($options as $index => $option) {
            $this->buildCore($index, $option);
            $this->setOutput($this->output);
        }

        $this->position = 'PREPEND';
        $output = $this->hookGet();
        $this->setOutput($output);

    }

    
}