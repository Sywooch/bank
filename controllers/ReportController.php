<?php

namespace app\controllers;

use Yii;
use app\models\searches\TransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\searches\DepositSearch;

/**
 * ReportController implements the CRUD actions for Transaction model.
 */
class ReportController extends Controller
{


    /**
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionOperationResults()
    {
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->operationResults();

        return $this->render('operation_results', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAverageByAge()
    {
        $searchModel = new DepositSearch();

        return $this->render('average_by_age', [
            'average' => $searchModel->averageByAge(),
        ]);
    }

    public function actionPrognosisProfit()
    {
        return $this->render('prognosis_profit', [

        ]);
    }
}
