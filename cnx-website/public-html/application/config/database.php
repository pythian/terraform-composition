<?php
use Doctrine\DBAL\DriverManager;

return [
    'default-connection' => 'concrete',
    'connections' => [
        'concrete' => [
            'driver' => 'concrete_pdo_mysql',
            'server' => getenv('MYSQL_ADDR', true) ?: getenv('MYSQL_ADDR'),
            'database' => 'connex-db-live',
            'username' => getenv('MYSQL_USER', true) ?: getenv('MYSQL_USER'),
            'password' =>  getenv('MYSQL_PASSWD', true) ?: getenv('MYSQL_PASSWD'),
            'character_set' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ],
];

$conn = DriverManager::getConnection($connectionParams);
