<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Currency $model */

$this->title = Yii::t('backend', 'Create Currency');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Currencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="currency-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
