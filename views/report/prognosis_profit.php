<?php

use yii\helpers\Html;
use app\models\searches\DepositSearch;
use components\helpers\DateTimeHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\searches\DepositSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $total array */

$this->title = 'Прогнозируемая прибыль за квартал (3 месяца)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">
    <h2>
        <?= Html::encode($this->title) ?>:
        <span style="color: <?= $total['profit'] > 0 ? 'green' : 'red' ?>;">
            <?= number_format($total['profit'], 2, ',', '&nbsp;') ?>
        </span>
    </h2>

    <p>
        Прогноз по выплате процентов: <?= number_format($total['percent'], 2, ',', '&nbsp;') ?><br>
        Прогноз по начислению комиссии: <?= number_format($total['commission'], 2, ',', '&nbsp;') ?>
    </p>


    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => false,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'client.fullName',
            [
                'attribute' => 'dpst_current_sum',
                'value'     => function($model) {
                    return number_format($model->dpst_current_sum, 2, ',', ' ');
                },
            ],
            [
                'label' => 'Прогноз по процентам',
                'value' => function($model) {
                    return number_format($model->prognosis['percent'], 2, ',', ' ');
                },
            ],
            [
                'label' => 'Прогноз по комиссии',
                'value' => function($model) {
                    return number_format($model->prognosis['commission'], 2, ',', ' ');
                },
            ],
            [
                'label' => 'Прибыль банка',
                'value' => function($model) {
                    return number_format($model->prognosis['profit'], 2, ',', ' ');
                },
            ],
        ],
    ]);

    Pjax::end();
    ?>
</div>
