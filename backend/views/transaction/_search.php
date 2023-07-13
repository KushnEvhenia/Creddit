<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TransactionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'id_from') ?>

    <?= $form->field($model, 'id_to') ?>

    <?= $form->field($model, 'amount_from') ?>

    <?php // echo $form->field($model, 'amount_to') ?>

    <?php // echo $form->field($model, 'id_category') ?>

    <?php // echo $form->field($model, 'id_counterparty') ?>

    <?php // echo $form->field($model, 'file') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
