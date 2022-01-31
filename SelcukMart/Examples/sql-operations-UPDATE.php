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
        'type' => 'UPDATE',
        'table' => [
            'a_table'
        ]
    ],
    [
        'type' => 'SET',
        " tip='1', a='2', b='3', c='8' ",
    ],
    [
        'type' => 'WHERE',
        "(tip='1' OR a='2') AND (b='3' XOR C='8') AND c='123' ",
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