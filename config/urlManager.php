<?php

return [
    'class'           => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName'  => false,
    'normalizer'      => [
        'class'  => 'yii\web\UrlNormalizer',
        'action' => \yii\web\UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
    ],
    'rules'           => [
        '<_c:[\w\-]+>/<id:\d+>'              => '<_c>/view',
        '<_c:[\w\-]+>'                       => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
//        '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
    ],
];