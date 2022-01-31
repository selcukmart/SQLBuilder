<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:45
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;
use SelcukMart\SQLOperations\SQLBuilderHook;

class ORDERBY extends AbstractCommands implements CommandsInterface
{
    

    

    

    public function build(array $options)
    {

        $this->output = ' ORDER BY ';
        $this->setOutput($this->output);

        $this->hook_core_key = 'ORDERBY';
        $this->i = 0;
        $this->total = _sizeof($options);
        $this->SQLBuilderHook = new SQLBuilderHook($this->SQLBuilder->getId());

        $this->position = 'APPEND';
        $output = $this->hookGet();
        if (!empty($output)) {
            $this->output .= $output;
            if ($this->total >1) {
                $this->output .= ', ';
            }
        }
        $this->setOutput($this->output);

        foreach ($options as $index => $option) {
            $this->buildCore($index, $option);
            if (!empty($this->output)) {
                if ($this->total !== $this->i) {
                    $this->output .= ', ';
                }
                $this->setOutput($this->output);
            }
        }

        $this->position = 'PREPEND';
        $output = $this->hookGet();
        if (!empty($output) && $this->total > 0) {
            $output = ', ' . $output;
        }

        $this->setOutput($output);
    }

    
}