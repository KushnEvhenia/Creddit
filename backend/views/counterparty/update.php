<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Counterparty $model */

$this->title = Yii::t('backend', 'Update Counterparty: ') . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Counterparties'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend','Update');
?>
<div class="counterparty-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
