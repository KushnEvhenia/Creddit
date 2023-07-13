<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Currency $model */

$this->title = Yii::t('backend','Update Currency: ') . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Currencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend','Update');
?> 
<div class="currency-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
