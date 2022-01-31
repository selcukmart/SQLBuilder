<?php 
use SelcukMart\SQLBuilder;
require(__DIR__ . '/../../vendor/autoload.php'); 
$ana_sql = [
    1 => [
        'type' => 'INSERT',
        'table' => [
            'abc'
        ]
    ],
    2 => [
        'type' => 'SET',
        0 => [
            'a.column1' => 'value1',
            'b.column2' => 'value2',
            'column3' => 'value3'
        ]
    ],
    3 => [
        'type' => 'WHERE',
        'WHERE' => ' a = 1 AND b = C '
    ]
]; $sql = new SQLBuilder();
$sql->build($ana_sql);
echo $sql->getOutput();
c($sql->getOutputFormatted());
c($ana_sql);
