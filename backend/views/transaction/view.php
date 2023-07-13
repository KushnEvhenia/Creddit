<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Account;
use app\models\Category;
use app\models\Counterparty;
use app\models\transaction;

/** @var yii\web\View $this */
/** @var app\models\Transaction $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend','Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label'=>Yii::t('backend', 'Type'),
                'value' => Yii::t('backend', $model->type),
            ],
            [                      
                'label' => Yii::t('backend', 'From'),
                'value' => '<a target = "_blank" href="/user/view?id=' . $model->id_from . '">' . Account::findOne($model->id_from)->name . '</a>',
                'format' => 'raw',
            ],
            [                      
                'label' => Yii::t('backend','To'),
                'value' => '<a target = "_blank" href="/user/view?id=' . $model->id_to . '">' . Account::findOne($model->id_to)->name . '</a>',
                'format' => 'raw',
            ],
            'amount_from',
            'amount_to',
            [                      
                'label' => Yii::t('backend','Category'),
                'value' => '<a target = "_blank" href="/category/view?id=' . $model->id_category . '">' . Category::findOne($model->id_category)->name . '</a>',
                'format' => 'raw',
            ],
            [                      
                'label' => Yii::t('backend','Counterparty'),
                'value' => '<a target = "_blank" href="/counterparty/view?id=' . $model->id_counterparty . '">' . Counterparty::findOne($model->id_counterparty)->name . '</a>',
                'format' => 'raw',
            ],
            [                      
                'label' => Yii::t('backend','File'),
                'value' => Transaction::display_links($model),
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
