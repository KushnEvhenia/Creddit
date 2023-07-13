<?php

use app\models\Account;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Currency;

/** @var yii\web\View $this */
/** @var app\models\AccountSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('backend','Accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend','Create Account'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'initial_amount',
            'current_amount',
            [
                'attribute' => 'id_currency',
                'value' => function($model){
                    return Currency::find()->where(['id'=>$model->id_currency])->one()->name;
                }
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Account $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        ]); 
    ?>


</div>
