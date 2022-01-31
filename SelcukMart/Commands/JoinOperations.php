<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:55
 */

namespace SelcukMart\Commands;

use SelcukMart\SQLBuilder;
use SelcukMart\Tools\SQLToolsTrait;

class JoinOperations
{
    use SQLToolsTrait;

    private
        $options,
        $output = '',
        $table = '',
        $table_as = '',
        $SQLBuilder;

    public function __construct(array $options, SQLBuilder $SQLBuilder)
    {
        $this->SQLBuilder = $SQLBuilder;
        $this->options = $options;
    }

    public function build()
    {
        foreach ($this->options as $operation => $option) {
            if(isset($option['type'])){
                $operation = $option['type'];
                unset($option['type']);
            }
            $operation = strtolower($operation);
            if ($operation == 'where') {
                $this->setOutput(' AND ');
                $this->$operation($option);
            } else {
                $this->$operation($option);
            }

        }

        return $this->getOutput();
    }

    private function on($option)
    {
        $output = ' ON ';
        $this->setOutput($output);
        $a = 0;
        foreach ($option as $index => $item) {
            $output = '';
            $a++;
            if (is_array($item)) {
                $this->SQLBuilder->build();
            } else {
                if ($a == 1) {
                    if (is_numeric($index)) {
                        $output = $this->table_as . '.' . $item;
                    } else {
                        $output = $index . '.' . $item;
                    }
                }
                if ($a == 2) {
                    $output = ' = ' . $index . '.' . $item;
                }
            }
            $this->setOutput($output);
        }
    }


    /**
     * @param string $output
     * @param bool $use_brackets
     */
    public function setOutput(string $output): void
    {
        $this->output .= $output;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}