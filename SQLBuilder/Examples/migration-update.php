<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 17:17
 */


use Brick\VarExporter\VarExporter;
use SelcukMart\Migration2SQLBuilder\Migration2SQLBuilder;
use SelcukMart\SQLBuilder;

require(__DIR__ . '/../../vendor/autoload.php');

//$migration = new Migration();
//$migration->extractSQL();

$sql = "UPDATE abc 
SET  a.column1=value1, b.column2=value2, column3=value3
WHERE a='1' AND b='C' LIMIT 10";
$migration = new Migration2SQLBuilder();
$output = $migration->sqlBuilder($sql);

$export_filename = 'sql-update-example.php';

include __DIR__.'/export-file.php';