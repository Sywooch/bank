<?php

namespace app\models\queries;

use yii\db\ActiveQuery;
use app\models\Deposit;

/**
 * This is the ActiveQuery class for [[\app\models\Deposit]].
 *
 * @see \app\models\Deposit
 */
class DepositQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function openThisDay()
    {
        $currentDay = (int) date('j');
        if(date('t') == $currentDay) {
            return $this->andWhere(['>=', 'DAYOFMONTH(dpst_created)', $currentDay]);
        }

        return $this->andWhere(['DAYOFMONTH(dpst_created)' => $currentDay]);
    }

    /**
     * @return $this
     */
    public function closed()
    {
        return $this->andWhere(['dpst_status' => Deposit::STATUS_CLOSED]);
    }

    /**
     * @return $this
     */
    public function draft()
    {
        return $this->andWhere(['dpst_status' => Deposit::STATUS_DRAFT]);
    }

    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['dpst_status' => Deposit::STATUS_ACTIVE]);
    }

    /**
     * @param integer $id
     *
     * @return $this
     */
    public function byId($id)
    {
        return $this->andWhere(['dpst_id' => $id]);
    }

    /**
     * @inheritdoc
     * @return \app\models\Deposit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Deposit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
