<?php

$localParams = is_file(__DIR__.'/params-local.php') ? require(__DIR__.'/params-local.php') : [];

$params = [
    'defaultPageSize' => 10,

    'commissionPlans' => [
        [
            'min'     => 0,
            'max'     => 1000,
            'percent' => 5,
            'min_sum' => 50,
        ],
        [
            'min'     => 1000.01,
            'max'     => 10000,
            'percent' => 6,
        ],
        [
            'min'     => 10000.01,
            'percent' => 7,
            'max_sum' => 5000,
        ],
    ],
];

return \yii\helpers\ArrayHelper::merge($params, $localParams);
