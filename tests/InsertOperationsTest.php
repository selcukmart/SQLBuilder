<?php
/**
 * @author selcukmart
 * 31.01.2022
 * 16:33
 */

use PHPUnit\Framework\TestCase;
use SelcukMart\Commands\JoinOperations;
use SelcukMart\SQLBuilder;

class InsertOperationsTest extends TestCase
{

    public function testExport()
    {
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
                    'column1' => 'value1',
                    'column2' => 'value2',
                    'column3' => 'value3'
                ]
            ],
            3 => [
                'type' => 'WHERE',
                'WHERE' => ' a = 1 AND b = C '
            ]
        ]; $sql = new SQLBuilder();
        $sql->build($ana_sql);
        $output =  trim($sql->getOutput());
        $expected = "INSERT INTO abc  SET column1 = value1, column2 = value2, column3 = value3 WHERE  a = 1 AND b = C";

        $this->assertSame($expected,$output);
    }

    public function testExportTest()
    {
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
        ];
        $sql = new SQLBuilder();
        $sql->build($ana_sql);
        $output =  trim($sql->getOutput());
        $expected = "INSERT INTO abc  SET a.column1 = value1, b.column2 = value2, column3 = value3 WHERE  a = 1 AND b = C";
        $this->assertSame($expected,$output);
    }
}
