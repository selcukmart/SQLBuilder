<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 15:36
 */

namespace SelcukMart\Commands;


use SelcukMart\SQLBuilder;

interface CommandsInterface
{

    public function __construct(SQLBuilder $SQLBuilder);

    public function build(array $options);

    public function __destruct();
}