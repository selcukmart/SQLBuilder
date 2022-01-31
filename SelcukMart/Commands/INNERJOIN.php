<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:51
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;

class INNERJOIN extends AbstractCommands implements CommandsInterface
{
    
    public function build(array $options)
    {
        $output = ' INNER JOIN ';
        $this->setOutput($output);
        $join = new JoinOperations($options, $this->SQLBuilder);
        $output = $join->build();
        $this->setOutput($output);
    }

    
}