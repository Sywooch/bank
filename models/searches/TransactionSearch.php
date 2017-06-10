<?php

namespace app\models\searches;

use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use app\models\Transaction;

/**
 * TransactionSearch represents the model behind the search form about `app\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    public $month;
    public $sum;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trsn_id', 'trsn_deposit_id', 'trsn_type', 'trsn_status'], 'integer'],
            [['trsn_sum'], 'number'],
            [['trsn_created', 'trsn_comment', 'month', 'sum'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance
     *
     * @return SqlDataProvider
     */
    public function operationResults()
    {
        $sql = "SELECT
                  SUM(sum) as sum,
                  month
                FROM (
                  SELECT
                    SUM(CASE WHEN trsn_type = :type_commission THEN trsn_sum * -1 ELSE trsn_sum END) as sum,
                    DATE_FORMAT(trsn_created, '%m.%Y') as month
                  FROM bank_transactions
                  WHERE trsn_status = :status
                  GROUP BY trsn_type, month
                ) as by_types
                GROUP BY month";
        $binds = [
            ':type_commission' => Transaction::TYPE_COMMISSION_ACCRUAL,
            ':status' => Transaction::STATUS_SUCCESS
        ];

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => $binds,
            'sort' => [
                'attributes' => [
                    'month',
                    'sum',
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->conf->get('defaultPageSize', 10),
            ],
        ]);

        return $dataProvider;
    }
}
