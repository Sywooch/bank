<?php

use yii\db\Migration;

class m170609_200555_create_table_transactions extends Migration
{
    private $_tableName = '{{%transactions}}';

    public function safeUp()
    {
        $tableOptions = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->_tableName, [
            'trsn_id'         => $this->primaryKey(),
            'trsn_deposit_id' => $this->integer()->notNull()->comment('Депозит'),
            'trsn_type'       => $this->smallInteger()->notNull()->comment('Тип транзакции'),
            'trsn_sum'        => $this->decimal(12, 2)->notNull()->defaultValue(0)->comment('Сумма транзакции'),
            'trsn_created'    => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата проводки'),
            'trsn_status'     => $this->smallInteger()->notNull()->defaultValue(0)->comment('Статус'),
            'trsn_comment'    => $this->text()->comment('Примечание'),
        ], $tableOptions);

        $this->addForeignKey('trsn_deposit_id_fk', $this->_tableName, 'trsn_deposit_id', '{{%deposits}}', 'dpst_id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable($this->_tableName);
    }
}
