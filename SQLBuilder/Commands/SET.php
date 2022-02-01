<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:29
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;
use SelcukMart\SQLOperations\SQLBuilderHook;

class SET extends AbstractCommands implements CommandsInterface
{

    public function build(array $options)
    {

        $this->output = ' SET ';
        $this->setOutput($this->output);
        $this->hook_core_key = 'SET';

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
        $this->i = 0;
        if (is_array($options[0])) {

            $options = $options[0];
            $this->total = _sizeof($options);

            foreach ($options as $index => $option) {
                $this->i++;
                $this->output = $index . ' = ' . $option;

                if ($this->total > $this->i) {
                    $this->output .= ', ';
                }
                $this->setOutput($this->output);

            }
        } else {

            $this->total = _sizeof($options);
            foreach ($options as $index => $option) {
                $this->buildCore($index, $option);
                if ($this->total !== $this->i) {
                    $this->output .= ', ';
                }
                $this->setOutput($this->output);
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

    
}