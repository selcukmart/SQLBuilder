<?php
/**
 * @author selcukmart
 * 31.01.2022
 * 16:33
 */

use PHPUnit\Framework\TestCase;
use SelcukMart\Commands\JoinOperations;
use SelcukMart\SQLBuilder;

class UpdateOperationsTest extends TestCase
{

    public function testExport()
    {

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
        ]; $sql = new SQLBuilder();
        $sql->build($ana_sql);
        $output =  trim($sql->getOutput());
        $expected = "UPDATE abc  SET a.column1 = value1, b.column2 = value2, column3 = value3 WHERE  a = 1 AND b = C  LIMIT 10";

        $this->assertSame($expected,$output);
    }

}
