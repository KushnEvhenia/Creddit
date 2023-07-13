<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 *
 * @property Transaction[] $transactions
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('backend', 'Name'),
        ];
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['id_category' => 'id']);
    }

    public static function createChart($date_from, $date_to){
        $cat_count = [];
        $res = [];
        $models = Transaction::find()->where([
            'between', 
            'updated_at',
            $date_from,
            $date_to,
        ])->all();
        if(empty($models)){
            return $res;
        }
        foreach($models as $model){
            $cat_count[] = Category::find()->where(['id' => $model->id_category])->one()->name;
        }
        $cat_count = array_count_values($cat_count);
        $all = array_sum($cat_count);
        foreach($cat_count as $key=>$value){
            $res[$key] = round($value*100/$all);
        }
        return $res;
    }
   
}
