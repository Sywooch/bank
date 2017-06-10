<?php

namespace app\models\helpers;

use Yii;
use yii\base\InvalidParamException;

class CommissionPlans
{
    public static function all()
    {
        $all = Yii::$app->conf->get('commissionPlans');
        if(!$all) {
            throw new InvalidParamException('Not defined commissionPlans [params.php]');
        }

        return $all;
    }

    public static function bySum($sum)
    {
        $all = self::all();

        $result = array_filter($all, function($elem) use ($sum) {

            if(!isset($elem['min'])) {
                throw new InvalidParamException('Param `min` is required in commissionPlans [params.php]');
            }

            if(!isset($elem['percent'])) {
                throw new InvalidParamException('Param `percent` is required in commissionPlans [params.php]');
            }

            $min = $sum >= $elem['min'];
            $max = !isset($elem['max']) || $sum <= $elem['max'];

            return $min && $max;
        });

        $result = current($result);
        $commission = $sum * $result['percent'] / 100;

        if(!empty($result['min_sum']) && $commission < $result['min_sum']) {
            $commission = $result['min_sum'];
        } elseif(!empty($result['max_sum']) && $commission > $result['max_sum']) {
            $commission = $result['max_sum'];
        }

        return round($commission, 2);
    }
}