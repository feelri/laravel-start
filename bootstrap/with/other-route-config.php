<?php

$prefix = [
    'data'  => [
        [
            'middleware' => [
                'api',
                'auth:user',
            ],
            'prefix'     => 'user-api',
            'group'      => __DIR__ . '/../../routes/user-api.php',
        ],
        [
            'middleware' => [
                'api',
                'auth:admin',
				'permission.admin',
				"limiter",
            ],
            'prefix'     => 'admin-api',
            'group'      => __DIR__ . '/../../routes/admin-api.php',
        ]
    ],
];

$prefix['patterns'] = [];
foreach ($prefix['data'] as $prefixKey => $prefixValue) {
    $prefix['patterns'][] = $prefixValue['prefix'];
    $prefix['patterns'][] = "{$prefixValue['prefix']}/*";
}

return $prefix;
