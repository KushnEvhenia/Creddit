<?php

namespace backend\controllers;

use app\models\Currency;

class DaemonController extends \yii\web\Controller
{
    public function actionUpdate()
    { 
        $result = file_get_contents('http://api.exchangeratesapi.io/v1/latest?access_key=71438a37f40b5af103ee78776933712d');
        $rates_arr = json_decode($result, true)['rates'];
        $base_name = json_decode($result, true)["base"];
        if(empty(Currency::find()->all())){
            foreach($rates_arr as $name=>$value){
                $model = new Currency;
                $model->name = $name;
                $model->value = $value;
                $model->default = ($name == $base_name) ? 1 : 0;
                $model->save();
            }
        }
        else{
            if(!Currency::find()->where(['default'=>1])->count()){
                foreach(Currency::find()->all() as $model){
                    if(!empty($rates_arr[$model->name])){
                        $model->value = $rates_arr[$model->name];
                        $model->default = ($model->name == $base_name) ? 1 : 0;
                        $model->save();
                    }
                }
            }
            else{
                $base_value = Currency::find()->where(['default'=>1])->one()->value;
                $main_factor = 1/$base_value;
                foreach(Currency::find()->all() as $model){
                    $model->value = $main_factor*$model->value;
                    $model->save();
                }
            }
        }
        return $this->redirect('/currency/index');
    }

}
