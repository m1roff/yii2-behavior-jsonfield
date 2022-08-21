<?php

use yii\db\Connection;

return [
    'id' => 'yii2-behavior-jsonfield',
    'name' => 'Yii2 Behavior JSON fields test',
    'basePath' => dirname(__DIR__, 2),
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=mysql;dbname=behavior',
            'username' => 'user',
            'password' => 'password',
            'charset' => 'utf8',
        ],
    ],
];
