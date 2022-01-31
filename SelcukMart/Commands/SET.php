<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:29
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;
use SelcukMart\SQLOperations\SQLBuilderHook;

class SET implements CommandsInterface
{
    use CommandsTrait;

    private $SQLBuilder;

    public function __construct(SQLBuilder $SQLBuilder)
    {
        $this->SQLBuilder = $SQLBuilder;
    }

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
                $this->i++;
                $this->output = '';

                if (is_numeric($index) && is_array($option)) {
                    $this->SQLBuilder->build($option);
                } else {
                    if (is_string($index) || is_array($option)) {
                        $this->output = $this->concateTheCommas($index, $option);
                    } else {
                        $this->output = $option;
                    }
                }
                if ($this->total != $this->i) {
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

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}