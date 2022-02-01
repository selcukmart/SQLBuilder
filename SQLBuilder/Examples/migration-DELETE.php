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

$sql = "DELETE FROM abc 
WHERE a='1' AND b='C' LIMIT 10";
$migration = new Migration2SQLBuilder();
$output = $migration->sqlBuilder($sql);
$export_filename = 'sql-delete-example.php';

include __DIR__.'/export-file.php';