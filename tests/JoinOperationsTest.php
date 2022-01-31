<?php
/**
 * @author selcukmart
 * 31.01.2022
 * 16:33
 */

use PHPUnit\Framework\TestCase;
use SelcukMart\Commands\JoinOperations;
use SelcukMart\SQLBuilder;

class JoinOperationsTest extends TestCase
{

    public function testExport()
    {

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
        $output =  trim($operations->getOutput());
        $expected = "a b  ON b.id = c.kisi_id AND (b.tip='1' AND b.a='2') || (b.b='3' XOR b.C='8') AND b.c='123'";

        $this->assertSame($expected,$output);
    }

}
