<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Transaction $model */

$this->title = Yii::t('backend', 'Update Transaction: ') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="transaction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'action' => 'update',
    ]) ?>

</div>
