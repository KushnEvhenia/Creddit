<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "account".
 *
 * @property int $id
 * @property string $name
 * @property int|null $amount
 * @property int|null $id_currency
 *
 * @property Currency $currency
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'initial_amount', 'current_amount', 'id_currency'], 'required'],
            [['id_currency'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['id_currency'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::class, 'targetAttribute' => ['id_currency' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' =>  'ID',
            'name' => Yii::t('backend','Name'),
            'initial_amount' => Yii::t('backend','Initial amount'),
            'current_amount' => Yii::t('backend','Current amount'),
            'id_currency' => Yii::t('backend','Id Currency'),
        ];
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'id_currency']);
    }
}
