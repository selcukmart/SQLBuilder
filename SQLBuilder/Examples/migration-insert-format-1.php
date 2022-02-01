<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 17:17
 */


use SelcukMart\Migration2SQLBuilder\Migration2SQLBuilder;

require(__DIR__ . '/../../vendor/autoload.php');

//$migration = new Migration();
//$migration->extractSQL();

$sql = "INSERT INTO abc (column1, column2, column3)
VALUES (value1, value2, value3) WHERE a='1' AND b='C' ";
$migration = new Migration2SQLBuilder();
$output = $migration->sqlBuilder($sql);
$export_filename = 'sql-insert-format-1-example.php';

include __DIR__.'/export-file.php';