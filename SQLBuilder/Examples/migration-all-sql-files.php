<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 17:17
 */


use SelcukMart\Migration2SQLBuilder\Migration2SQLBuilder;

require(__DIR__ . '/../../vendor/autoload.php');

$migration = new Migration2SQLBuilder();
$migration->extractSQL();
