<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Currency $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="currency-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= 
        $form->field($model, 'default')->dropDownList(
            ['1' => Yii::t('backend', 'Yes'), '0' => Yii::t('backend', 'No')]
        );  
    ?>

    <?= $form->field($model, 'value')->textInput([
        "type" => "number",
        "step" => "0.01",
        'min' => 0.01,
    ]) 
    ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
