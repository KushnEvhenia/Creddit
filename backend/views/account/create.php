<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Account $model */

$this->title = Yii::t('backend','Create Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_creation_form', [
        'model' => $model,
    ]) ?>

</div>
