<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:51
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;

class AAS extends AbstractCommands implements CommandsInterface
{
    public function build(array $options)
    {
        $output = ' AS ';
        $this->SQLBuilder->setDestructOutput($output . $options[0]);
    }

    
}