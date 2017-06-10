<?php

namespace app\models;

use components\helpers\DateTimeHelper;
use Yii;
use yii\db\ActiveRecord;
use app\models\queries\DepositQuery;
use app\models\helpers\CommissionPlans;

/**
 * This is the model class for table "{{%deposits}}".
 *
 * @property integer $dpst_id
 * @property integer $dpst_client_id
 * @property integer $dpst_status
 * @property float $dpst_percent
 * @property float $dpst_start_sum
 * @property float $dpst_current_sum
 * @property string $dpst_created
 * @property string $dpst_updated
 * @property string $dpst_expiry
 *
 * @property integer $id
 * @property float $commissionSum
 * @property string $statusName
 * @property Client $client
 * @property Transaction[] $transactions
 */
class Deposit extends ActiveRecord
{
    const STATUS_DRAFT  = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 2;

    private $_monthPercent = null;
    private $_monthCommission = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%deposits}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dpst_client_id', 'dpst_status'], 'integer'],

            [['dpst_percent', 'dpst_start_sum', 'dpst_current_sum'], 'number'],

            ['dpst_status', 'default', 'value' => self::STATUS_DRAFT],
            ['dpst_status', 'in', 'range' => array_keys(self::getStatuses())],

            [['dpst_client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['dpst_client_id' => 'clnt_id']],

            [['dpst_created', 'dpst_updated', 'dpst_expiry'], 'safe'],
        ];
    }

    public function percentAccrual()
    {
        $this->dpst_current_sum = round(($this->dpst_current_sum + $this->getMonthPercent()), 2);

        return $this->dpst_current_sum;
    }

    public function commissionAccrual()
    {

        $this->dpst_current_sum = round(($this->dpst_current_sum - $this->getMonthCommission()), 2);

        return $this->dpst_current_sum;
    }

    /**
     * Current percent sum
     *
     * @param bool $force
     *
     * @return float
     */
    public function getMonthPercent($force = false)
    {
        if($this->_monthPercent === null || $force === true) {
            $monthPercent = $this->dpst_current_sum / 12;
            $sum = $this->dpst_percent * $monthPercent / 100;
            $this->_monthPercent = round($sum, 2);
        }

        return $this->_monthPercent;
    }


    /**
     * Getting a sum of commission
     *
     * @param bool $force
     *
     * @return float
     */
    public function getMonthCommission($force = false)
    {
        if($this->_monthCommission === null || $force === true) {
            if($this->dpst_current_sum == 0) {
                $this->_monthCommission = 0;
            } else {
                $this->_monthCommission = CommissionPlans::bySum($this->dpst_current_sum);
                if(DateTimeHelper::isPreviousMonth($this->dpst_created)) {
                    $daysInMonth = DateTimeHelper::getDaysInMonth($this->dpst_created);
                    $currentDay = DateTimeHelper::getDayOfMonth($this->dpst_created);
                    $this->_monthCommission = round($this->_monthCommission / ($daysInMonth - $currentDay + 1), 2);
                }

                if($this->_monthCommission > $this->dpst_current_sum) {
                    $this->_monthCommission = $this->dpst_current_sum;
                }
            }
        }

        return $this->_monthCommission;
    }

    /**
     * Status name
     *
     * @return string
     */
    public function getStatusName()
    {
        return self::getStatuses()[$this->dpst_status] ?? '';
    }

    /**
     * Statuses list
     *
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT  => 'Черновик',
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_CLOSED => 'Закрытый',

        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->dpst_id;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dpst_id'          => 'ID',
            'dpst_client_id'   => 'Клиен',
            'dpst_status'      => 'Статус депозита',
            'dpst_percent'     => 'Процентная ставка',
            'dpst_start_sum'   => 'Первоначальный взнос',
            'dpst_current_sum' => 'Текущая сумма',
            'dpst_created'     => 'Дата обновления',
            'dpst_updated'     => 'Дата обновления',
            'dpst_expiry'      => 'Дата окончания действия',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['clnt_id' => 'dpst_client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['trsn_deposit_id' => 'dpst_id']);
    }

    /**
     * @inheritdoc
     * @return DepositQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DepositQuery(get_called_class());
    }
}
