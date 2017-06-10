<?php

use yii\db\Migration;

class m170609_192637_create_table_clients extends Migration
{
    private $_tableName = '{{%clients}}';

    public function safeUp()
    {
        $tableOptions = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->_tableName, [
            'clnt_id'       => $this->primaryKey(),
            'clnt_id_code'  => $this->integer()->notNull()->comment('Идентификационный код'),
            'clnt_name'     => $this->string(255)->notNull()->comment('Имя'),
            'clnt_surname'  => $this->string(255)->notNull()->comment('Фамилия'),
            'clnt_sex'      => $this->string(2)->notNull()->comment('Пол'),
            'clnt_birthday' => $this->date()->notNull()->comment('Дата рождения'),
        ], $tableOptions);

        $this->createIndex('clnt_id_code_udx', $this->_tableName, 'clnt_id_code', true);
    }

    public function safeDown()
    {
        $this->dropTable($this->_tableName);
    }
}
