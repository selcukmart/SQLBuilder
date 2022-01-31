<?php
/**
 * @author selcukmart
 * 16.05.2021
 * 18:48
 */


use SelcukMart\SQLBuilder;

require(__DIR__ . '/../../vendor/autoload.php');

$vtable = 'aasaas';
$vtable_as = 'aas';

$vtable2 = 'basaas';
$vtable2_as = 'baa';

//$ana_sql = [
//    1 => [
//        "type" => "SELECT",
//        "$vtable_as" => [
//            "* "
//        ],
//        "$vtable2_as" => [
//            "isim AS KURUM_SUBE ",
//            "tip AS NESNE_TIPI "
//        ]
//    ],
//    2 => [
//        "type" => "FROM",
//        0 => "$vtable",
//        1 => "$vtable_as"
//    ],
//    3 => [
//        "type" => "LEFT JOIN",
//        "table" => [
//            "$vtable2",
//            "$vtable2_as"
//        ],
//        "ON" => [
//            "$vtable2_as" => "id",
//            "$vtable_as" => "veri_id"
//        ]
//    ],
//    4 => [
//        "type" => "WHERE",
//        "WHERE" => " $vtable_as.tip = ".Nesne::MD_TIP." AND $vtable_as.tip2 = ".YDS2Adobe::MD_TIP." AND $vtable2_as.tip IN ( 16 , 28 ) "
//    ]
//];
$ana_sql = [
    1 => [
        'type' => 'UPDATE',
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
    ],
    4 => [
        'type' => 'LIMIT',
        0 => '10'
    ]
];

$sql = new SQLBuilder();
$sql->build($ana_sql);
echo $sql->getOutput();
c($sql->getOutputFormatted());