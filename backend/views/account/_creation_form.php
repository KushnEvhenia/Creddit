<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Currency;

/** @var yii\web\View $this */
/** @var app\models\Account $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'initial_amount')->textInput(['type' => 'number', 'step' => 0.01]) ?>

    <?= 
        $form->field($model, 'id_currency')->dropDownList(
            ArrayHelper::map(Currency::find()->all(), 'id', 'name'),
            ['prompt'=>'Select currency']
        ); 
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
