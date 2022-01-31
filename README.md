## SQL Builder

You can use this library for creating sql over sql array

Supported only SELECT,INSERT, UPDATE, DELETE; others are planned. You can build unlimitted subqueries.

```
SELECT
INSERT
UPDATE
DELETE
~~REPLACE~~
~~RENAME~~
~~SHOW~~
~~SET~~
~~DROP~~
~~CREATE INDEX~~
~~CREATE TABLE~~
~~EXPLAIN~~
~~DESCRIBE~~
~~TRUNCATE~~
```

### Common SQL Builder Usage;

Every SQLBuilder should have an ID if you have more builders on same way. You can get the ID
calling `$sql_builder->getId()`

`echo $sql->getOutputFormatted();` is formatted view;

`echo $sql->getOutput();` is the real usage view;

```php
$sql_generator = [
    /**
    * optional, for more builders on same way
    */
    'id'=>123,
    /**
    * Select array
    */
    [
        'type' => 'SELECT',        
        'c',
        'd.f',
        'a' => '*',
        'b' => 'sube_id,ana_yetki_id,ad AS KUL_AD,soyad AS KUL_SOYAD',
        'bx' => [
        'branch_id',
        'auth_id',
        'name AS USER_NAME',
        'surname AS USER_SURNAME',
        ],
        'c' => 'name PAYMENT_OPTION',
        'd' => 'name AS CARGO_FIRM',
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
        'e' => 'id AS INVOICE_ID, x_firm_invoice_id,       
        trackable_job_id,
        status,
        proccess_status,
        sent,
        pdf_url,       
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
$sql->build($sql_generator);

/**
 * Formatted Output
 */
echo $sql->getOutputFormatted();

/**
 * Highlighted False Output
 */
echo $sql->getOutputFormatted(false);

/**
 * Real usage output
 */
echo $sql->getOutput();
```

#### Example Output

[example]:https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/1-1621003248-ok-screenshot-at-may-14-17-40-27.png "Example Output"
![Example Output][example]

### Joins usage;

> Order is very important!!
>
> Example;
>
> Wrong: 'ON'=>['SSDSD'],'table'=>[]
>
> Right: 'table'=>[],'ON'=>['SSDSD'],'WHERE'

```php
$arr = [
    'TABLE' => [
        /**
         * also works
         * type=>'TABLE',
         */
        'a',
        'b'
    ],
    'ON' => [
        /**
         * also works
         * type=>'ON',
         */
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
    /**
     * [ $vtable_as=>"(tip='1' || a='2') || (b='3' XOR C='8') AND c='123' "]
     */
    'WHERE' => [
        /**
         * also works
         * type=>'WHERE',
         */
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
```

The same;

```php
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
         * this is this as or this table
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
```

### UPDATE

```php

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
```

### DELETE

```php

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

```

### INSERT

```php

$sql_generator = [
    [
        'type' => 'INSERT',
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
    ]
];

$sql = new SQLBuilder();
$sql->build($sql_generator);
echo $sql->getOutput();
c($sql->getOutputFormatted());

```
