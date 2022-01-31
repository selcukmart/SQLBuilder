<?php
/**
 * @author selcukmart
 * 31.01.2022
 * 16:33
 */

use PHPUnit\Framework\TestCase;
use SelcukMart\Commands\JoinOperations;
use SelcukMart\SQLBuilder;

class SQLOperationsTest extends TestCase
{

    public function testExport()
    {
        $sql_generator = [
            [
                'type' => 'SELECT',
                'c',
                'd.f',
                'a' => '*',
                'b' => 'sube_id,ana_yetki_id,ad AS KUL_AD,soyad AS KUL_SOYAD',
                'c' => 'isim ODEME_SECENEKLERI',
                'd' => 'isim AS KARGO_FIRMASI',
                [
                    [
                        'type' => 'SELECT',
                        'b.*',
                        [
                            [
                                'type' => 'SELECT',
                                'b.*'
                            ],
                            [
                                'type' => 'FROM',
                                'b'
                            ]
                        ]
                    ],
                    [
                        'type' => 'FROM',
                        'b'
                    ]
                ],
                'e' => 'id AS INVOICE_ID, parasut_fatura_id,
        e_fatura_or_arsiv,
        parasut_e_fatura_id,
        trackable_job_id,
        status,
        proccess_status,
        sent,
        pdf_url,
        kargo_gonderildi,
        kargo_firmasina_bildirildi,
        bildirim_tarihi',
                'd.x',
                'c.y',
                'j'
            ],
            [
                'type' => 'FROM',
                [
                    [
                        'type' => 'SELECT',
                        'b.*'
                    ],
                    [
                        'type' => 'FROM',
                        'b'
                    ]
                ],
                'AS a'
            ],
            [
                'type' => 'INNER JOIN',
                'table' => [
                    [
                        [
                            'type' => 'SELECT',
                            'b.*'
                        ],
                        [
                            'type' => 'FROM',
                            'b'
                        ]
                    ],
                    'b'
                ],
                'ON' => [
                    /**
                     * 'b' => 'id',
                     */
                    'id',
                    /**
                     * this ise this as or this table
                     * but for others write the table
                     * or table as
                     */
                    'a' => 'kisi_id'
                ],
                /**
                 * [ 'a'=>"(tip='1' OR a='2') OR (b='3' XOR C='8') AND c='123' "]
                 */
                'WHERE' => [
                    /**
                     * Ön Tanımlı join içinde olduğundan 'b' olacaktır.
                     */
                    "(tip='1' OR a='2') OR (b='3' XOR C='8') AND c='123' ",

                ]
            ],
            [
                'type' => 'LEFT JOIN',
                'table' => [
                    'qwer',
                    'q'
                ],
                'ON' => [
                    'id',
                    'a' => 'teslimat_adresi_id'
                ]
            ],
            [
                'type' => 'LEFT JOIN',
                'table' => [
                    'asd',
                    'asertt'
                ],
                'ON' => [
                    'id',
                    'a' => 'fatura_adresi_id'
                ]
            ],
            [
                'type' => 'LEFT JOIN',
                'table' => [
                    'ahjghhj',
                    'ah'
                ],
                'ON' => [
                    'id',
                    'q' => 'il'
                ],
                'WHERE' => [
                    " kume_id='0' "
                ]
            ],
            [
                'type' => 'LEFT JOIN',
                'table' => [
                    'bvnbvnvbn',
                    'bvn'
                ],
                'ON' => [
                    'id',
                    'asertt' => 'il'
                ],
                'WHERE' => [
                    " kume_id='0' "
                ]
            ],
            [
                'type' => 'WHERE',
                'a' => "(tip='1' OR a='2') AND (b='3' XOR C='8') AND d.c='123' ",
                //'b' => "(tip='1' OR a='2') OR (b='3' XOR C='8') AND c='123' ",
                //"(tip='1' OR a='2') OR (b='3' XOR C='8') AND c='123' ",
                // OR
                //"(a.tip='1' OR a.a='2') OR (a.b='3' XOR a.C='8') AND a.c='123' "
            ],
            [
                'type' => 'GROUP BY',
                'a' => "id, sira ",
                'c' => 'x,Y,Z',
                'k,val'
            ],
            [
                'type' => 'LIMIT',
                10
            ]
        ];

        $sql = new SQLBuilder();
        $sql->build($sql_generator);
        $output =  trim($sql->getOutput());
        $expected = "SELECT c, company_id,d.f, company_id,a.*, company_id,b.sube_id,b.ana_yetki_id,b.ad AS KUL_AD,b.soyad AS KUL_SOYAD, company_id,c.isim ODEME_SECENEKLERI, company_id,d.isim AS KARGO_FIRMASI, company_id,(SELECT b.*, company_id,(SELECT b.*company_id, FROM b )company_id, FROM b ), company_id,e.id AS INVOICE_ID,e.parasut_fatura_id,e.e_fatura_or_arsiv,e.parasut_e_fatura_id,e.trackable_job_id,e.status,e.proccess_status,e.sent,e.pdf_url,e.kargo_gonderildi,e.kargo_firmasina_bildirildi,e.bildirim_tarihi, company_id,d.x, company_id,c.y, company_id,jcompany_id, FROM (SELECT b.*company_id, FROM b )AS a  INNER JOIN (SELECT b.*company_id, FROM b )b  ON b.id = a.kisi_id AND (b.tip='1' OR b.a='2') OR (b.b='3' XOR b.C='8') AND b.c='123'  LEFT JOIN qwer q  ON q.id = a.teslimat_adresi_id LEFT JOIN asd asertt  ON asertt.id = a.fatura_adresi_id LEFT JOIN ahjghhj ah  ON ah.id = q.il AND  ah.kume_id='0'  LEFT JOIN bvnbvnvbn bvn  ON bvn.id = asertt.il AND  bvn.kume_id='0'  WHERE (a.tip='1' OR a.a='2') AND (a.b='3' XOR a.C='8') AND d.c='123'  GROUP BY a.id,a.sira, c.x,c.Y,c.Z, k,val LIMIT 10";

        $this->assertSame($expected,$output);
    }

}
