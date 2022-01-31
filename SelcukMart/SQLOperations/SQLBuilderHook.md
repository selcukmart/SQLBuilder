### SQLBUILDER HOOKS
You can modify SQL built with SQLBuilder with hooks. SQL Hooks works only first depth of the SQL Builder. So you can not modify subqueries.

```php
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
                    ],
                    [
                        /**
                         * AS is reserved, because of it we are using AAS
                         * AS = AAS
                         */
                        'type' => 'AAS',
                        /**
                         * Default this is true
                         * You have to use this only sub
                         * Otherwise you don't need AAS
                         */
                        'sub' => true,
                        'aaa'
                    ]
                ],
            ],
            [
                'type' => 'FROM',
                'b'
            ],
            [
                /**
                 * AS is reserved, because of it we are using AAS
                 * AS = AAS
                 */
                'type' => 'AAS',
                'BBB'
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
        'asdfg',
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
$id = 123;
$sql->setId($id);
/**
 * Builder and hook have the same ID 
 */
$sql_builder_hook = new SQLBuilderHook($id);
$sql_hooks = [
    [
        'position' => 'prepend',
        'key' => 'join',
        'str' => 'INNER JOIN A AS B ON b.id=x.hid'
    ],
    [
        'position' => 'prepend',
        'key' => 'join',
        'order' => -1,
        'str' => 'INNER JOIN dA AS dB ON b.did=dx.hid'
    ],
    [
        'position' => 'append',
        'key' => 'join',
        'str' => 'INNER JOIN AX AS BX ON bX.id=xX.hid'
    ],
    [
        'position' => 'append',
        'key' => 'select',
        'str' => 'asd'
    ],
    [
        'position' => 'prepend',
        'key' => 'select',
        'str' => 'asd'
    ],
    [
        'position' => 'append',
        'key' => 'where',
        'str' => 'asd  AND',
    ],
    [
        'position' => 'prepend',
        'key' => 'where',
        'str' => 'AND asd ',
    ],
    [
        'position' => 'append',
        'key' => 'groupby',
        'str' => ' asd ASC'
    ],
    [
        'position' => 'prepend',
        'key' => 'groupby',
        'str' => 'xasd ASC'
    ],
    [
        'position' => 'prepend',
        'key' => 'limit',
        'str' => ' 100x '
    ],
    [
        'position' => 'append',
        'key' => 'limit',
        'str' => ' 100c '
    ]
];
$sql_builder_hook->add($sql_hooks);
$sql->build($sql_generator);
c($sql->getOutputFormatted());
```

#### Hook Conf
`'position' => 'prepend',` or `'position' => 'append',`
With position, you can set the sql top or end of the sql statement.

`'key' => 'join',`

Key List;
```
SELECT
INSERT
UPDATE
DELETE
ALL JOINS(INNER, OUTER ETC)
LIMIT
GROUPBY
ORDERBY
SET
WHERE
FROM
```

```php
[
        /**
        * append
        * prepend
        */
        'position' => 'prepend',
        'key' => 'join',
        'str' => 'INNER JOIN A AS B ON b.id=x.hid'
    ];
```

### ID
Builder and hook must have the same ID
```php
$sql = new SQLBuilder();
$id = 123;
$sql->setId($id);
/**
 * Builder and hook must have the same ID 
 */
$sql_builder_hook = new SQLBuilderHook($id);
```