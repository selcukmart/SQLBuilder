<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:51
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;

class AAS implements CommandsInterface
{
    use CommandsTrait;

    private $SQLBuilder;

    public function __construct(SQLBuilder $SQLBuilder)
    {
        $this->SQLBuilder = $SQLBuilder;
    }

    public function build(array $options)
    {
        $output = ' AS ';
        $this->SQLBuilder->setDestructOutput($output . $options[0]);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}