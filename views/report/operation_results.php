<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\searches\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Прибыль/убыток по месяцам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => false,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'month',
                'label' => 'Месяц'
            ],
            [
                'attribute' => 'sum',
                'label' => 'Прибыль/убыток'
            ],
        ],
    ]);

    Pjax::end();
    ?>
</div>
