<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=yiitest',
            'username' => 'postgres',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
];