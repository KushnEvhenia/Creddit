<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Account;
use app\models\Transaction;
use app\models\Category;
use app\models\Counterparty;
use app\models\Currency;


/** @var yii\web\View $this */
/** @var app\models\Transaction $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="transaction-form">
    <ul class="nav nav-tabs" name='type'>
        <li class="nav-item">
          <a class="nav-link active" href="/transaction/<?=$action?>?id=<?=$model->id?>"><?= Yii::t('backend', 'Transfer') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/transaction/expense?id=<?=$model->id?>"><?= Yii::t('backend', 'Expense')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/transaction/income?id=<?=$model->id?>"><?= Yii::t('backend','Income')?></a>
        </li>
    </ul>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
       
        <?= 
            $form->field($model, 'id_from')->dropDownList(
               
                Transaction::changeOptionText(ArrayHelper::map(Account::find()->all(), 'id', 'name')),
                [
                    'options' => Transaction::setCurrencyRateAttribute(),
                    'prompt' => Yii::t('backend', 'Select account')
                ]

            );
                
        ?>

        <?= 
            $form->field($model, 'id_to')->dropDownList(

                Transaction::changeOptionText(ArrayHelper::map(Account::find()->all(), 'id', 'name')),
                [
                    'options' => Transaction::setCurrencyRateAttribute(),
                    'prompt' => Yii::t('backend', 'Select account'),
                    'onchange' => 'getInputValue()',
                ]

            );  
        ?>
        
        <?= $form->field($model, 'amount_from')->textInput(['type' => 'number','min' => 0.01, 'step' => 0.01, 'oninput'=>'getInputValue()']) ?>

        <?= $form->field($model, 'amount_to')->textInput(['type' => 'number', 'min' => 0.01, 'step' => 0.01]) ?>

        <?= 
            $form->field($model, 'id_category')->dropDownList(
                ArrayHelper::map(Category::find()->all(), 'id', 'name'),
                ['prompt'=> Yii::t('backend','Select category')]
            ); 
        ?>

        <?= $form->field($model, 'file[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])  ?>
        
        <div class="form-group">
            <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
