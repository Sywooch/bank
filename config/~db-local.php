<?php

return [
    'class'             => 'yii\db\Connection',
    'dsn'               => 'mysql:host=localhost;dbname=bank',
    'username'          => 'root',
    'password'          => '',
    'charset'           => 'utf8',
    'tablePrefix'       => 'bank_',
    'enableSchemaCache' => @(YII_DEBUG ? false : true),
];
