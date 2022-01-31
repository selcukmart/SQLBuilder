<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:51
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;

class RIGHTJOIN implements CommandsInterface
{
    use CommandsTrait;

    private $SQLBuilder;

    public function __construct(SQLBuilder $SQLBuilder)
    {
        $this->SQLBuilder = $SQLBuilder;
    }

    public function build(array $options)
    {
        $output = ' RIGHT JOIN ';
       $this->setOutput($output);
        $join = new JoinOperations($options, $this->SQLBuilder);
        $output = $join->build();
        $this->setOutput($output);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}