<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Account $model */

$this->title = Yii::t('backend','Update Account: ') . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend','Update');
?>
<div class="account-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_updation_form', [
        'model' => $model,
    ]) ?>

</div>
