<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:38
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLOperations\SQLBuilderHook;

trait CommandsTrait
{
    public function setOutput(&$output)
    {
        $this->SQLBuilder->setOutput($output);
        $output = '';
    }

    private function hookGet()
    {
        $output = '';
        if ($this->SQLBuilder->getDepth() == 1) {
            $hook_key = SQLBuilderHook::key($this->hook_core_key, $this->position, $this->SQLBuilder->getId());
            $output = $this->SQLBuilderHook->get($hook_key);
        }
        return $output;
    }

    private function concateTheCommas($index, $option)
    {
        $output = '';
        if (is_array($option)) {
            $columns = $option;
        } else {
            $columns = explode(',', $option);
        }
        $total = _sizeof($columns);
        $i = 0;
        foreach ($columns as $column) {
            $i++;
            $column = trim($column);
            if (preg_match('/\./', $column)) {
                $output .= $column;
            } else {
                $output .= $index . '.' . $column;
            }
            if ($i < $total) {
                $output .= ',';
            }
        }
        return $output;
    }

    public function commandPrepare($command)
    {
        return $this->SQLBuilder->commandPrepare($command);
    }

}