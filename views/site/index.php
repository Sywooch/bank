<?php
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h2>Отчеты</h2>
        <p>
            <a class="btn btn-lg btn-info" href="<?= Url::to(['/report/operation-results']) ?>">Прибыль/убыток</a>
            <a class="btn btn-lg btn-info" href="<?= Url::to(['/report/average-by-age']) ?>">Средняя сумма депозита</a>
            <a class="btn btn-lg btn-info" href="<?= Url::to(['/report/prognosis-profit']) ?>">Прогнозируемая прибыль</a>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <pre>
            <?php

            ?>
                </pre>
        </div>

    </div>
</div>
