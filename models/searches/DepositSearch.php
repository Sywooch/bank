<?php

namespace app\models\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Deposit;

/**
 * DepositSearch represents the model behind the search form about `app\models\Deposit`.
 */
class DepositSearch extends Deposit
{
    const AGE_CATEGORY_I     = 1;
    const AGE_CATEGORY_II    = 2;
    const AGE_CATEGORY_III   = 3;
    const AGE_CATEGORY_OTHER = -1;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dpst_id', 'dpst_client_id', 'dpst_status'], 'integer'],
            [['dpst_percent', 'dpst_start_sum', 'dpst_current_sum'], 'number'],
            [['dpst_created', 'dpst_updated', 'dpst_expiry'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public static function ageCategories()
    {
        return [
            self::AGE_CATEGORY_I     => 'от 18 до 24 лет',
            self::AGE_CATEGORY_II    => 'от 25 до 49 лет',
            self::AGE_CATEGORY_III   => 'от 50 лет',
            self::AGE_CATEGORY_OTHER => 'другие (до 18-ти)',
        ];
    }

    public function prognosis($params = [])
    {
        $query = Deposit::find()
            ->joinWith(['client'])
            ->orderBy(['dpst_created' => SORT_ASC])
            ->active();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => Yii::$app->conf->get('defaultPageSize', 10),
            ],
            'sort'       => false,
        ]);

        if($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function averageByAge()
    {
        $query = Deposit::find()
            ->select([
                'AVG(dpst_current_sum) as average',
                'count(dpst_id) as count',
                "year(now()) - year(clnt_birthday) - (if (DATE_FORMAT(now(),'%m%d') >= DATE_FORMAT(clnt_birthday,'%m%d'), 0, 1)) as full_years",
            ])
            ->innerJoin('{{%clients}}', 'clnt_id = dpst_client_id')
            ->active()
            ->groupBy('full_years');

        $average = $query->asArray()->all();
        $result = $this->_initialAverageArray();

        foreach($average as $item) {
            if($item['full_years'] >= 18 && $item['full_years'] <= 24) {
                $idxA = self::AGE_CATEGORY_I;
            } elseif($item['full_years'] >= 25 && $item['full_years'] <= 49) {
                $idxA = self::AGE_CATEGORY_II;
            } elseif($item['full_years'] >= 50) {
                $idxA = self::AGE_CATEGORY_III;
            } else {
                $idxA = self::AGE_CATEGORY_OTHER;
            }
            $result[$idxA]['sum'] += $item['average'];
            $result[$idxA]['count'] += $item['count'];
        }

        return $result;
    }

    private function _initialAverageArray()
    {
        $result = [];
        foreach(self::ageCategories() as $idx => $item) {
            $result[$idx] = [
                'sum'   => 0,
                'count' => 0,
            ];
        }

        return $result;
    }
}