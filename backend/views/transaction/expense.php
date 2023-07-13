<?php

use yii\helpers\Html;
//use Yii;

/** @var yii\web\View $this */
/** @var app\models\Transaction $model */

$this->title = Yii::t('backend', $title) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_expense_form', [
        'model' => $model,
        'action' => $action,
    ]) ?>

</div>