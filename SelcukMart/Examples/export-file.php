<?php
/**
 * @author selcukmart
 * 16.05.2021
 * 19:34
 */

use Brick\VarExporter\VarExporter;

file_put_contents($export_filename,'<?php 
use SelcukMart\SQLBuilder; 
require(__DIR__ . \'/../../vendor/autoload.php\'); 
$ana_sql = ' . VarExporter::export($output) . '; $sql = new SQLBuilder();
$sql->build($ana_sql);
echo $sql->getOutput();
c($sql->getOutputFormatted());
c($ana_sql);
');
