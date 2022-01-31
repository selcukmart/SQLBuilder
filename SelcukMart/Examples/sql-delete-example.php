<?php 
use SelcukMart\SQLBuilder;
require(__DIR__ . '/../../vendor/autoload.php');
$ana_sql = [
    1 => [
        'type' => 'DELETE'
    ],
    2 => [
        'type' => 'FROM',
        0 => 'abc'
    ],
    3 => [
        'type' => 'WHERE',
        'WHERE' => ' a = 1 AND b = C '
    ],
    4 => [
        'type' => 'LIMIT',
        0 => '10'
    ]
]; $sql = new SQLBuilder();
$sql->build($ana_sql);
echo $sql->getOutput();
c($sql->getOutputFormatted());
c($ana_sql);
