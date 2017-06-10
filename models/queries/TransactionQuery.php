<?php

namespace app\models\queries;

use yii\db\ActiveQuery;
use app\models\Transaction;

/**
 * This is the ActiveQuery class for [[\app\models\Transaction]].
 *
 * @see \app\models\Transaction
 */
class TransactionQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function thisMonth()
    {
        return $this->andWhere(["DATE_FORMAT(trsn_created, '%Y-%c')" => date('Y-n')]);
    }

    /**
     * @param integer $depositId
     *
     * @return $this
     */
    public function byDeposit($depositId)
    {
        return $this->andWhere(['trsn_deposit_id' => $depositId]);
    }

    /**
     * @return $this
     */
    public function unsuccessful()
    {
        return $this->andWhere(['trsn_status' => Transaction::STATUS_FAIL]);
    }

    /**
     * @return $this
     */
    public function successful()
    {
        return $this->andWhere(['trsn_status' => Transaction::STATUS_SUCCESS]);
    }

    /**
     * @return $this
     */
    public function onlyCommission()
    {
        return $this->andWhere(['trsn_type' => Transaction::TYPE_COMMISSION_ACCRUAL]);
    }

    /**
     * @return $this
     */
    public function onlyDeposit()
    {
        return $this->andWhere(['trsn_type' => Transaction::TYPE_PERCENT_ACCRUAL]);
    }

    /**
     * @param integer $id
     *
     * @return $this
     */
    public function byId($id)
    {
        return $this->andWhere(['trsn_id' => $id]);
    }

    /**
     * @inheritdoc
     * @return \app\models\Transaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Transaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
