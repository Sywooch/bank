<?php

namespace app\models\queries;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\Client]].
 *
 * @see \app\models\Client
 */
class ClientQuery extends ActiveQuery
{
    /**
     * @param integer $idCode
     *
     * @return $this
     */
    public function byIdCode($idCode)
    {
        return $this->andWhere(['clnt_id_code' => $idCode]);
    }

    /**
     * @param integer $id
     *
     * @return $this
     */
    public function byId($id)
    {
        return $this->andWhere(['clnt_id' => $id]);
    }

    /**
     * @inheritdoc
     * @return \app\models\Client[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Client|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
