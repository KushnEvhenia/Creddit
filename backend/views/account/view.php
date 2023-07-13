<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Currency;

/** @var yii\web\View $this */
/** @var app\models\Account $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend','Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'initial_amount',
            'current_amount',
            [
                'attribute' =>'id_currency',
                'value' => Currency::find()->where(['id'=>$model->id_currency])->one()->name,
            ],
        ],
    ]) ?>

</div>
