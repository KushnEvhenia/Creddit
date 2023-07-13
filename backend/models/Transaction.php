<?php

namespace app\models;

use Yii;

use yii\helpers\ArrayHelper;

use app\models\Account;

use app\models\Currency;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property string $type
 * @property int|null $id_from
 * @property int|null $id_to
 * @property float|null $amount_from
 * @property float|null $amount_to
 * @property int|null $id_category
 * @property int|null $id_counterparty
 * @property string|null $file
 * @property int $user_id
 *
 * @property Category $category
 * @property Counterparty $counterparty
 * @property User $from
 * @property User $to
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{

    const SCENARIO_TRANSFER  = 'create_transfer';
    const SCENARIO_EXPENSE = 'create_exchange';
    const SCENARIO_INCOME = 'create_income';

    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_from', 'id_to', 'amount_from', 'amount_to', 'id_category'], 'required', 'on' => self::SCENARIO_TRANSFER],
            [['amount_from', 'id_from', 'id_category'], 'required', 'on' => self::SCENARIO_EXPENSE],
            [['amount_to', 'id_to', 'id_category'], 'required', 'on' => self::SCENARIO_INCOME],
            //[['id_from', 'id_to', 'id_category', 'id_counterparty', 'user_id'], 'integer'],
            [['amount_from', 'amount_to'], 'number'],
            [['type',], 'string', 'max' => 255],
            [['id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['id_category' => 'id']],
            [['id_counterparty'], 'exist', 'skipOnError' => true, 'targetClass' => Counterparty::class, 'targetAttribute' => ['id_counterparty' => 'id']],
            [['id_from'], 'exist', 'skipOnError' => true, 'targetClass' => Account::class, 'targetAttribute' => ['id_from' => 'id']],
            [['id_to'], 'exist', 'skipOnError' => true, 'targetClass' => Account::class, 'targetAttribute' => ['id_to' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => Yii::t('backend', 'Type'),
            'id_from' => Yii::t('backend', 'From'),
            'id_to' => Yii::t('backend', 'To'),
            'amount_from' => Yii::t('backend', 'Amount From'),
            'amount_to' =>  Yii::t('backend', 'Amount To'),
            'id_category' =>  Yii::t('backend', 'Category'),
            'id_counterparty' =>  Yii::t('backend', 'Counterparty'),
            'file' =>  Yii::t('backend', 'File'),
            'user_id' =>  Yii::t('backend', 'User ID'),
            
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'id_category']);
    }

    /**
     * Gets query for [[Counterparty]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCounterparty()
    {
        return $this->hasOne(Counterparty::class, ['id' => 'id_counterparty']);
    }

    /**
     * Gets query for [[From]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFrom()
    {
        return $this->hasOne(User::class, ['id' => 'id_from']);
    }

    /**
     * Gets query for [[To]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTo()
    {
        return $this->hasOne(User::class, ['id' => 'id_to']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
    
    public static function display_links($model){

        $arr = explode(',', $model->file);
        $key = array_key_last($arr);
        unset($arr[$key]);
        $html = '';
        foreach($arr as $file){
            $html = $html. '<a target="_blank" href="/uploads/' . $file . '">' . $file. '</a></br>'; 
        }
        return $html;
    } 

    public static function changeOptionText($array){
        $res = [];
        foreach($array as $key=>$val){
            $currency_id = Account::find()->where(['id' => $key])->one()->id_currency;
            $currency_name = Currency::find()->where(['id' => $currency_id])->one()->name;
            $val = $val . '(' .  $currency_name. ')';
            $res[$key] = $val;
        }
        return $res;
    }

    public static function setCurrencyRateAttribute(){

        $options = [];

        foreach (Account::find()->joinWith('currency')->all() as $m) {
        
            $options[$m->id] = ['currency_rate' => $m->currency->value];
        
        }

        return $options;
    }

    public static function createChart($date_from, $date_to){
        $res = array(Yii::t('backend','Incomes')=>0, Yii::t('backend','Expenses')=>0);
        $transaction_incomes = Transaction::find()->where(['between', 'updated_at', $date_from, $date_to])->all();
        if(empty($transaction_incomes)){
            $res = [];
            return $res;
        }
        foreach($transaction_incomes as $transaction){
            if($transaction->type == 'Income'){
                $value = $transaction->amount_to;
                $currency_id = Account::find()->where(['id'=>$transaction->id_to])->one()->id_currency;
                $currency_rate = Currency::find()->where(['id'=>$currency_id])->one()->value;
                $res[Yii::t('backend','Incomes')] += round($value/$currency_rate);
            }
            if($transaction->type == 'Expense'){
                $value = $transaction->amount_from;
                $currency_id = Account::find()->where(['id'=>$transaction->id_from])->one()->id_currency;
                $currency_rate = Currency::find()->where(['id'=>$currency_id])->one()->value;
                $res[Yii::t('backend','Expenses')] += round($value/$currency_rate);
            }
        }
        return $res;
    }
}
