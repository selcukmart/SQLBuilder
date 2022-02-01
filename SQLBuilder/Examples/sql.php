[
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
]