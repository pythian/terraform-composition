<?php

return [
    'default-connection' => 'concrete',
    'connections' => [
        'concrete' => [
            'driver' => 'concrete_pdo_mysql',
            'server' => 'cnx-dev-cus-webdb.mysql.database.azure.com',
            'database' => 'connex-db-live',
            'username' => 'connexusenergy_user_2024',
            'password' => 'REPLACEME',
            'character_set' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ],
];
