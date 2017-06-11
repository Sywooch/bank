<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\queries\TransactionQuery;

/**
 * This is the model class for table "{{%transactions}}".
 *
 * @property integer $trsn_id
 * @property integer $trsn_deposit_id
 * @property integer $trsn_type
 * @property string $trsn_sum
 * @property string $trsn_created
 * @property integer $trsn_status
 * @property string $trsn_comment
 *
 * @property integer $id
 * @property string $statusName
 * @property string $typeName
 * @property Deposit $deposit
 */
class Transaction extends ActiveRecord
{
    const TYPE_PERCENT_ACCRUAL    = 1;
    const TYPE_COMMISSION_ACCRUAL = 2;

    const STATUS_FAIL    = 0;
    const STATUS_SUCCESS = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transactions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trsn_deposit_id', 'trsn_type'], 'required'],
            [['trsn_deposit_id', 'trsn_type', 'trsn_status'], 'integer'],
            [['trsn_sum'], 'number'],
            ['trsn_comment', 'string'],

            ['trsn_type', 'in', 'range' => array_keys(self::getTypes())],

            ['trsn_status', 'default', 'value' => self::STATUS_FAIL],
            ['trsn_status', 'in', 'range' => array_keys(self::getStatuses())],

            [['trsn_deposit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deposit::className(), 'targetAttribute' => ['trsn_deposit_id' => 'dpst_id']],

            [['trsn_created'], 'safe'],
        ];
    }

    /**
     * @param int $depositId
     *
     * @return bool
     */
    public static function isInterestAccrued($depositId)
    {
        return self::isAccrued($depositId, self::TYPE_PERCENT_ACCRUAL);
    }

    /**
     * @param int $depositId
     *
     * @return bool
     */
    public static function isCommissionAccrued($depositId)
    {
        return self::isAccrued($depositId, self::TYPE_COMMISSION_ACCRUAL);
    }

    /**
     * @param int $depositId
     * @param int $type
     *
     * @return bool
     */
    public static function isAccrued($depositId, $type = self::TYPE_PERCENT_ACCRUAL)
    {
        $query = static::find()->byDeposit($depositId)->thisMonth()->successful();
        switch($type) {
            case self::TYPE_PERCENT_ACCRUAL:
                $query->onlyDeposit();
                break;
            case self::TYPE_COMMISSION_ACCRUAL:
                $query->onlyCommission();
                break;
        }

        return (bool) $query->count('trsn_id');
    }

    /**
     * Status name
     *
     * @return string
     */
    public function getStatusName()
    {
        return self::getTypes()[$this->trsn_type] ?? '';
    }

    /**
     * Type name
     *
     * @return string
     */
    public function getTypeName()
    {
        return self::getTypes()[$this->trsn_type] ?? '';
    }

    /**
     * Statuses list
     *
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_FAIL    => 'Ошибка',
            self::STATUS_SUCCESS => 'Успешно',
        ];
    }

    /**
     * Types list
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_PERCENT_ACCRUAL    => 'Начисление процентов по депозиту',
            self::TYPE_COMMISSION_ACCRUAL => 'Снятие комиссии',
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->trsn_id;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trsn_id'         => 'Trsn ID',
            'trsn_deposit_id' => 'Депозит',
            'trsn_type'       => 'Тип транзакции',
            'trsn_sum'        => 'Сумма транзакции',
            'trsn_created'    => 'Дата проводки',
            'trsn_status'     => 'Статус',
            'trsn_comment'    => 'Примечание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposit()
    {
        return $this->hasOne(Deposit::className(), ['dpst_id' => 'trsn_deposit_id']);
    }

    /**
     * @inheritdoc
     * @return TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionQuery(get_called_class());
    }
}
