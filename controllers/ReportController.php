<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Deposit;
use app\models\searches\DepositSearch;
use app\models\searches\TransactionSearch;

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
        $searchModel = new DepositSearch();
        $dataProvider = $searchModel->prognosis();

        $total = [
            'percent'    => 0,
            'commission' => 0,
            'profit'     => 0,
        ];

        if(!Yii::$app->request->isAjax) {
            $deposits = Deposit::find()->active()->all();
            foreach($deposits as $deposit) {
                $prognosis = $deposit->getPrognosis();
                $total['percent'] += $prognosis['percent'] ?? 0;
                $total['commission'] += $prognosis['commission'] ?? 0;
                $total['profit'] += $prognosis['profit'] ?? 0;
            }
        }

        return $this->render('prognosis_profit', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'total'        => $total,
        ]);
    }
}
