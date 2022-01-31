<?php
/**
 * @author selcukmart
 * 31.01.2022
 * 16:33
 */

use PHPUnit\Framework\TestCase;
use SelcukMart\Commands\JoinOperations;
use SelcukMart\SQLBuilder;

class DeleteOperationsTest extends TestCase
{

    public function testExport()
    {

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
        $output =  trim($sql->getOutput());
        $expected = "DELETE  FROM abc  WHERE  a = 1 AND b = C  LIMIT 10";

        $this->assertSame($expected,$output);
    }

}
