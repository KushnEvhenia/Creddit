<?php

use app\models\Transaction;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Account;
use app\models\Category;
use app\models\Counterparty;

/** @var yii\web\View $this */
/** @var app\models\TransactionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title =Yii::t('backend', 'Transactions');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="transaction-index">
 
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend','Create Transaction'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=> function($model){
            if($model->type == 'Income'){
                return ['style' => 'background-color: #e3fbe3;'];
            }  
            elseif($model->type == 'Expense') {
                return ['style' => 'background-color: #fbe3e3;'];
            }
            else{
                return ['style' => 'background-color: #efe3fb;'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\serialColumn'],
            'id',
            [
                'attribute' => 'type',
                'value' => function($model, $key){
                    return Yii::t('backend', Transaction::find()->where(['id'=> $key])->one()->type);
                },
                'filter' => Html::activeDropDownList($searchModel, 'type', ArrayHelper::map(Transaction::find()->asArray()->all(), 'type', 'type'),['class'=>'form-control','prompt' => Yii::t('backend', 'All')])
            ],
            [
                'attribute' => 'id_from',
                'value' => function($model){
                    return Account::find()->where(['id'=> $model->id_from])->one()->name;
                },   
            ],
            [
                'attribute' => 'id_to',
                'value' => function($model){
                    return Account::find()->where(['id'=> $model->id_to])->one()->name;
                },   
            ],
            'amount_from',
            'amount_to',
            [
                'attribute' => 'id_category',
                'value' => function($model){
                    return Category::find()->where(['id'=> $model->id_category])->one()->name;
                },   
            ],
            [
                'attribute' => 'id_counterparty',
                'value' => function($model){
                    return Counterparty::find()->where(['id'=> $model->id_counterparty])->one()->name;
                },   
            ],
            'file',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Transaction $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

</div>
