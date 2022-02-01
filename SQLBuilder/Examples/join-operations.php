<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 17:17
 */

use SelcukMart\Commands\JoinOperations;
use SelcukMart\SQLBuilder;

require(__DIR__ . '/../../vendor/autoload.php');

$arr = [
    [
        'type' => 'TABLE',
        'a',
        'b'
    ],
    [
        'type' => 'ON',
        /**
         * $vtable4_as => 'id',
         */
        'id',
        /**
         * this ise this as or this table
         * but for others write the table
         * or table as
         */
        'c' => 'kisi_id'
    ],

    [
        'type' => 'WHERE',
        /**
         * Ön Tanımlı join içinde olduğundan $vtable4_as olacaktır.
         */
        "(tip='1' AND a='2') || (b='3' XOR C='8') AND c='123' ",

    ]
];
$SQLBuilder = new SQLBuilder();
$operations = new JoinOperations($arr, $SQLBuilder);
$operations->build();
echo $operations->getOutput();