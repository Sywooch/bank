<?php

use yii\db\Migration;

class m170609_193438_create_table_deposits extends Migration
{
    private $_tableName = '{{%deposits}}';

    public function safeUp()
    {
        $tableOptions = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->_tableName, [
            'dpst_id'          => $this->primaryKey(),
            'dpst_client_id'   => $this->integer()->comment('Клиен'),
            'dpst_status'      => $this->smallInteger()->notNull()->defaultValue(0)->comment('Статус депозита'),
            'dpst_percent'     => $this->decimal(5, 2)->notNull()->defaultValue(0)->comment('Процентная ставка'),
            'dpst_start_sum'   => $this->decimal(12, 2)->notNull()->defaultValue(0)->comment('Первоначальный взнос'),
            'dpst_current_sum' => $this->decimal(12, 2)->notNull()->defaultValue(0)->comment('Текущая сумма'),
            'dpst_created'     => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата обновления'),
            'dpst_updated'     => $this->timestamp()->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP')->comment('Дата обновления'),
            'dpst_expiry'      => $this->date()->comment('Дата окончания действия'),
        ], $tableOptions);

        $this->addForeignKey('dpst_client_id_fk', $this->_tableName, 'dpst_client_id', '{{%clients}}', 'clnt_id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable($this->_tableName);
    }
}
