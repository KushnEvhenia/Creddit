<?php

use app\models\Counterparty;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CounterpartySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title =  Yii::t('backend','Counterparties');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="counterparty-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend','Create Counterparty'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'type',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Counterparty $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
