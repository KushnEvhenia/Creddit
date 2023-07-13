<?php

namespace app\models;

use Yii;

use app\models\Transaction;

use app\models\Account;

/**
 * This is the model class for table "counterparty".
 *
 * @property int $id
 * @property string $name
 * @property string $type
 *
 * @property Transaction[] $transactions
 */
class Counterparty extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'counterparty';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['name', 'type'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['type'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' =>  Yii::t('backend','Name'),
            'type' =>  Yii::t('backend','Type'),
        ];
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['id_counterparty' => 'id']);
    }

    public static function createChart($date_from, $date_to){
        $counterparty_chart = [];
        foreach(Counterparty::find()->all() as $counterparty_model){
            $counterparty_chart[$counterparty_model->name]['exp'] = 0;
            $counterparty_chart[$counterparty_model->name]['inc'] = 0;
            $transactions = Transaction::find()->where(['id_counterparty'=>$counterparty_model->id])->andWhere(['between', 'updated_at', $date_from, $date_to,])->all();
            foreach($transactions as $transaction){
                if($transaction->type == 'Expense'){
                    $account_id = $transaction->id_from;
                    $currency_id = Account::find()->where(['id'=>$account_id])->one()->id_currency;
                    $currency_rate = Currency::find()->where(['id'=>$currency_id])->one()->value;
                    $counterparty_chart[$counterparty_model->name]['exp'] += round($transaction->amount_from/$currency_rate);
                }
                elseif($transaction->type == 'Income'){
                    $account_id = $transaction->id_to;
                    $currency_id = Account::find()->where(['id'=>$account_id])->one()->id_currency;
                    $currency_rate = Currency::find()->where(['id'=>$currency_id])->one()->value;
                    $res = round($transaction->amount_to/$currency_rate);
                    $counterparty_chart[$counterparty_model->name]['inc'] += $res;
                }
            }
        }
        return $counterparty_chart;
    }
}
