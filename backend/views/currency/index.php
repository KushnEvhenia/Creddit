<?php

use app\models\Currency;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\CurrencySerch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('backend', 'Currencies');
$this->params['breadcrumbs'][] = $this->title;

?>

<?
?>
<div class="currency-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend','Create Currency'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend', 'Update currency rates'), ['daemon/update'], ['class' => 'btn btn-primary', 'onclick'=>"show_warning()"]) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= 
        GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            [
                'attribute' => 'default',
                'value'=> function($model){
                    if($model->default == 0){
                        return Yii::t('backend', 'No');
                    }else{
                        return Yii::t('backend', 'Yes');
                    }
                },
            ],
            'value',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Currency $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        ]); 
    ?>


</div>
