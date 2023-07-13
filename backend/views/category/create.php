<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Category $model */

$this->title = 'Create Category';
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', $this->title);
?>
<div class="category-create">

    <h1><?= Yii::t('backend', Html::encode($this->title)) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
