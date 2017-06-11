<?php

use yii\helpers\Html;
use app\models\searches\DepositSearch;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $average array */

$this->title = 'Средняя сумма депозита по возрастным группам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Группа</th>
            <th>Количество</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($average) {
            foreach($average as $category => $data) {
                echo Html::beginTag('tr');
                echo Html::tag('td', DepositSearch::ageCategories()[$category] ?? '');
                echo Html::tag('td', $data['count']);
                echo Html::tag('td', number_format($data['sum'], 2, ',', '&nbsp;'));
                echo Html::endTag('tr');
            }
        } else {
            echo Html::beginTag('tr');
            echo Html::tag('td', 'Ничего не найдено.');
            echo Html::endTag('tr');
        }
        ?>
        </tbody>
    </table>
</div>
