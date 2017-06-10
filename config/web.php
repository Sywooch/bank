<?php

$config = [
    'id'         => 'basic',
    'name' => '3B-Bank',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'language' => 'ru-RU',
    'components' => [
        'request'      => [
            'cookieValidationKey' => 'OJIfUi0_LTkvwR7zxdTbNXWGtmEEMjRN',
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'user'         => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer'       => [
            'class'            => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'           => require(__DIR__.'/db-local.php'),
        'urlManager' => require(__DIR__.'/urlManager.php'),
        'conf'       => [
            'class' => 'components\Config',
        ],
    ],
    'params'     => require(__DIR__.'/params.php'),
];

if(YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
