<?php

namespace components;

use Yii;
use yii\helpers\ArrayHelper;

class Config
{
    /**
     * @param string $key ключ к параметру.
     * @param null|mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return ArrayHelper::getValue(Yii::$app->params, $key, $default);
    }
}