<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\queries\ClientQuery;

/**
 * This is the model class for table "{{%clients}}".
 *
 * @property integer $clnt_id
 * @property integer $clnt_id_code
 * @property string $clnt_name
 * @property string $clnt_surname
 * @property string $clnt_sex
 * @property string $clnt_birthday
 *
 * @property string $fullName
 * @property integer $id
 * @property Deposit[] $deposits
 */
class Client extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clients}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clnt_id_code', 'clnt_name', 'clnt_surname', 'clnt_sex', 'clnt_birthday'], 'required'],
            [['clnt_id_code'], 'integer'],
            [['clnt_birthday'], 'safe'],
            [['clnt_name', 'clnt_surname'], 'string', 'max' => 255],
            [['clnt_sex'], 'string', 'max' => 2],
            [['clnt_id_code'], 'unique'],
        ];
    }

    /**
     * Getting client full name
     *
     * @return string
     */
    public function getFullName()
    {
        return implode(' ', array_diff([$this->clnt_name, $this->clnt_surname], [null, '']));
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->clnt_id;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clnt_id'       => 'ID',
            'clnt_id_code'  => 'Идентификационный код',
            'clnt_name'     => 'Имя',
            'clnt_surname'  => 'Фамилия',
            'clnt_sex'      => 'Пол',
            'clnt_birthday' => 'Дата рождения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposits()
    {
        return $this->hasMany(Deposit::className(), ['dpst_client_id' => 'clnt_id']);
    }

    /**
     * @inheritdoc
     * @return ClientQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClientQuery(get_called_class());
    }
}
