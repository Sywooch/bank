<?php

namespace app\commands;

use components\helpers\DateTimeHelper;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Yii;
use app\models\Transaction;
use yii\console\Controller;
use app\models\Deposit;

class CronController extends Controller
{
    /**
     * @var \yii\db\Connection
     */
    private $_db;

    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_db = Yii::$app->db;
    }

    public function actionPercentAccrual($chunk = 1000)
    {
        $deposits = Deposit::find()->active()->openThisDay()->all();
        $this->_accrual($deposits, $chunk, Transaction::TYPE_PERCENT_ACCRUAL);
    }

    public function actionCommissionAccrual($chunk = 1000, $force = 0)
    {
        if(!$force && date('j') != 1) {
            echo 'Commission accrual cron can only be start on the first day of the month'.PHP_EOL;

            return;
        }
        $deposits = Deposit::find()->active()->all();
        $this->_accrual($deposits, $chunk, Transaction::TYPE_COMMISSION_ACCRUAL);
    }

    /**
     * @param Deposit[] $deposits
     * @param integer $chunk
     * @param integer $type
     *
     * @return boolean
     */
    private function _accrual($deposits, $chunk, $type)
    {
        if(!$deposits) {
            return;
        }

        foreach(array_chunk($deposits, $chunk) as $partDeposits) {
            $transactions = [];
            $dbTrans = $this->_db->beginTransaction();
            try {
                foreach($partDeposits as $deposit) {
                    $transaction = [];
                    /* @var $deposit Deposit */
                    //By transaction type
                    switch($type) {
                        case Transaction::TYPE_PERCENT_ACCRUAL:
                            $transaction = $this->_percentAccrual($deposit);
                            break;
                        case Transaction::TYPE_COMMISSION_ACCRUAL:
                            $transaction = $this->_commissionAccrual($deposit);
                            break;
                    }
                    if($transaction) {
                        $transactions[] = $transaction;
                    }
                }

                if($transactions) {
                    $this->_db->createCommand()
                        ->batchInsert('{{%transactions}}', ['trsn_deposit_id', 'trsn_type', 'trsn_sum', 'trsn_status'], $transactions)
                        ->execute();
                }
                $dbTrans->commit();
            } catch(\Exception $e) {
                $dbTrans->rollBack();
                $message = $e->getMessage();
                echo $message.PHP_EOL;

                if($transactions) {
                    $transactions = array_map(function($item) use ($message) {
                        $item['trsn_status'] = Transaction::STATUS_FAIL;
                        $item['trsn_comment'] = $message;

                        return $item;
                    }, $transactions);

                    $this->_db->createCommand()
                        ->batchInsert('{{%transactions}}', ['trsn_deposit_id', 'trsn_type', 'trsn_sum', 'trsn_status', 'trsn_comment'], $transactions)
                        ->execute();
                }
            }
        }
    }

    /**
     * @param Deposit $deposit
     *
     * @return array
     */
    private function _percentAccrual($deposit)
    {
        if(Transaction::isInterestAccrued($deposit->getId())) {
            return [];
        }

        $deposit->percentAccrual();
        if($deposit->save()) {
            return [
                'trsn_deposit_id' => $deposit->dpst_id,
                'trsn_type'       => Transaction::TYPE_PERCENT_ACCRUAL,
                'trsn_sum'        => $deposit->getMonthPercent(),
                'trsn_status'     => Transaction::STATUS_SUCCESS,
            ];
        }

        return [];
    }

    /**
     * @param Deposit $deposit
     *
     * @return array
     */
    private function _commissionAccrual($deposit)
    {
        if(Transaction::isCommissionAccrued($deposit->getId())) {
            return [];
        }

        //if need percent accrual
        if(DateTimeHelper::getDayOfMonth($deposit->dpst_created) == DateTimeHelper::getDayOfMonth()) {
            $this->_accrual([$deposit], 1, Transaction::TYPE_PERCENT_ACCRUAL);
        }

        $deposit->commissionAccrual();
        if($deposit->save()) {
            return [
                'trsn_deposit_id' => $deposit->dpst_id,
                'trsn_type'       => Transaction::TYPE_COMMISSION_ACCRUAL,
                'trsn_sum'        => $deposit->getMonthCommission(),
                'trsn_status'     => Transaction::STATUS_SUCCESS,
            ];
        }

        return [];
    }
}
