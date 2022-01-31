<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 17:17
 */

use SelcukMart\SQLBuilder;

require(__DIR__ . '/../../vendor/autoload.php');

$sql_generator = [
    [
        'type' => 'DELETE'
    ],
    [
        'type' => 'FROM',
        'a_table'
    ],
    [
        'type' => 'WHERE',
        'a' => "(tip='1' OR a='2') AND (b='3' XOR C='8') AND d.c='123' ",
    ],
    [
        'type' => 'LIMIT',
        10
    ]
];

$sql = new SQLBuilder();
$sql->build($sql_generator);
echo $sql->getOutput();
c($sql->getOutputFormatted());