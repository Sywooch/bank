<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use components\helpers\DateTimeHelper;
use app\models\Client;
use app\models\Deposit;

class DataController extends Controller
{
    public function actionFill()
    {
        $clients = 1000;
        $deposits = 2500;
        $trans = Yii::$app->db->beginTransaction();
        try {
            $this->fillClients($clients);
            $this->fillDeposits($deposits);
            $trans->commit();
        } catch(\Exception $e) {
            $trans->rollBack();
            echo "Error".$e->getMessage()."\n";
        }

    }

    protected function fillDeposits($count = 1000)
    {
        $clients = Client::find()->select('clnt_id')->indexBy('clnt_id')->asArray()->column();

        $deposits = [];
        for($i = 0; $i < $count; $i++) {
            $startSum = mt_rand(500, 500000);
            $created = DateTimeHelper::randomDate(1420070400, 1496948243);
            $mon = 60 * 60 * 24 * 30 * mt_rand(1, 60);
            $durationDeposit = strtotime($created) + $mon;

            $deposits[] = [
                'dpst_client_id'   => array_rand($clients),
                'dpst_status'      => $durationDeposit > time() ? mt_rand(Deposit::STATUS_DRAFT, Deposit::STATUS_ACTIVE) : Deposit::STATUS_CLOSED,
                'dpst_percent'     => mt_rand(5, 80),
                'dpst_start_sum'   => $startSum,
                'dpst_current_sum' => $startSum,
                'dpst_created'     => $created,
                'dpst_updated'     => date('Y-m-d H:i:s'),
                'dpst_expiry'      => date('Y-m-d', $durationDeposit),
            ];
        }
        $columns = array_keys($deposits[0]);
        $result = 0;
        foreach(array_chunk($deposits, 1000) as $item) {
            $insert = Yii::$app->db->createCommand()->batchInsert('{{%deposits}}', $columns, $item)->execute();
            if($insert) {
                $result += count($item);
            }
        }

        $this->stdout("Added {$result} deposits".PHP_EOL);
    }

    protected function fillClients($count = 1000)
    {
        $clients = [];
        $sex = ['m' => 1, 'f' => 1];
        $startTime = 384825600;
        $endTime = time() - (60 * 60 * 24 * 355 * 18);
        $codes = $this->_getRandomCodes($count);
        for($i = 0; $i < $count; $i++) {
            $clients[] = [
                'clnt_id_code'  => $codes[$i],
                'clnt_name'     => 'Name '.$i,
                'clnt_surname'  => 'Surname '.$i,
                'clnt_sex'      => array_rand($sex),
                'clnt_birthday' => date('Y-m-d', mt_rand($startTime, $endTime)),
            ];
        }

        $result = 0;
        foreach(array_chunk($clients, 1000) as $item) {
            $insert = Yii::$app->db->createCommand()->batchInsert('{{%clients}}', ['clnt_id_code', 'clnt_name', 'clnt_surname', 'clnt_sex', 'clnt_birthday'], $item)->execute();
            if($insert) {
                $result += count($item);
            }
        }

        $this->stdout("Added {$result} clients".PHP_EOL);
    }

    private function _getRandomCodes($count)
    {
        $existingCodes = Client::find()->select('clnt_id_code')->column();
        $result = [];
        $flag = 0;
        while(count($result) < $count && $flag < 1000000) {
            $flag++;
            $inlineFlag = 0;
            do {
                $random = mt_rand(1, 999999999);
                $condition = !in_array($random, $existingCodes) && !in_array($random, $result);
                if($condition) {
                    $result[] = $random;
                }
                $inlineFlag++;
            } while(!$condition && $inlineFlag < 100);

            $result = array_diff($result, $existingCodes);
            $result = array_unique($result);
        }

        return array_values($result);
    }
}