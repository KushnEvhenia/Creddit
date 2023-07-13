<?php

/** @var yii\web\View $this */
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Transaction;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use app\models\Account;
use app\models\Category;
use app\models\Counterparty;
use dosamigos\chartjs\ChartJs;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;

$this->title = Yii::t('backend','Main');

$first_date = Yii::t('backend', 'From date');

$last_date = Yii::t('backend', 'To date');

$layout = <<< HTML
<span class="input-group-text">$first_date</span>
{input1}
<span class="input-group-text">$last_date</span>
{input2}
HTML;

?>
<div class="container">
    <div class="row">
        <div class="col-6 mb-3">
            <? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <?=
                DatePicker::widget([
                   'name' => 'from_date',
                   'value' => $date_from,
                   'type' => DatePicker::TYPE_RANGE,
                   'name2' => 'to_date',
                   'value2' => $date_to,
                   'layout' => $layout,
                   'pluginOptions' => [
                       'autoclose' => true,
                       'format' => 'yyyy-mm-dd',
                       'todayHighlight' => true
                   ]
                ]);
            ?>
        </div>  
        <div class="col-6 mb-3">
            <button type="submit" class="btn btn-primary btn-btn btn-block"><?= Yii::t('backend', 'Show statistics') ?></button>
            <? ActiveForm::end(); ?> 
        </div>  
        <div class="w-100"></div>
        <?if(!empty($main_chart) && !empty($counterparty_chart) && !empty($cat_chart)):?>
            <div class="col-6 mb-3">
                <?= 
                    ChartJs::widget([
                        'type' => 'bar',
                        'id' => 'bar',
                        'options' => [
                            'height' => 200,
                            'width' => 400,
                        ],
                        'data' => [ 
                            'radius' =>  "90%",
                            'labels' => array_keys($counterparty_chart),
                            'datasets' => [
                                [
                                    'label' => Yii::t("backend", "Incomes"),
                                    'backgroundColor' => "rgba(154,255,154,1)",
                                    'borderColor' => "rgba(154,255,154,1)",
                                    'pointBackgroundColor' => "rgba(154,255,154,1)",
                                    'pointBorderColor' => "#fff",
                                    'pointHoverBackgroundColor' => "#fff",
                                    'pointHoverBorderColor' => "rgba(154,255,154,1)",
                                    'data' => array_map(function($array){
                                        return $array['inc'];
                                    }, $counterparty_chart, array())
                                ],
                                [
                                    'label' => Yii::t("backend","Expenses"),
                                    'backgroundColor' => "rgba(255,154,154,1)",
                                    'borderColor' => "rgba(255,154,154,1)",
                                    'pointBackgroundColor' => "rgba(255,154,154,1)",
                                    'pointBorderColor' => "#fff",
                                    'pointHoverBackgroundColor' => "#fff",
                                    'pointHoverBorderColor' => "rgba(255,154,154,1)",
                                    'data' => array_map(function($array){
                                        return $array['exp'];
                                    }, $counterparty_chart, array())
                                ]
                            ]
                        ],
                        'clientOptions' => [
                            'tooltips' => [
                                'enabled' => true,
                                'intersect' => true
                            ],
                            'hover' => [
                                'mode' => false
                            ],
                            'maintainAspectRatio' => false,
                        ],
                        'plugins' => [
                            new \yii\web\JsExpression('
                            [{
                                afterDatasetsDraw: function(chart, easing) {
                                    var ctx = chart.ctx;
                                
                                    chart.data.datasets.forEach(function (dataset, i) {
                                        var meta = chart.getDatasetMeta(i);
                                        if (!meta.hidden) {
                                            meta.data.forEach(function(element, index) {
                                                // Draw the text in black, with the specified font
                                                ctx.fillStyle = "rgb(0, 0, 0)";
                                                var fontSize = 16;
                                                var fontStyle = "normal";
                                                var fontFamily = "Helvetica";
                                                ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                                                // Just naively convert to string for now
                                                var dataString = dataset.data[index].toString()+"%";
                                                // Make sure alignment settings are correct
                                                ctx.textAlign = "center";
                                                ctx.textBaseline = "middle";
                                                var padding = 5;
                                                var position = element.tooltipPosition();
                                                ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                                            });
                                        }
                                    });
                                }
                            }],'),
                        ]
                    ]);
                ?>
            </div>
            <div class="col-6 mb-3">
                <?= 
                    ChartJs::widget([
                        'type' => 'horizontalBar',
                        'id' => 'horizontalbar',
                        'options' => [
                            'height' => 200,
                            'width' => 400,
                        ],
                        'data' => [ 
                            'radius' =>  "90%",
                            'labels' => array_keys($main_chart),
                            'indexAxis'=> 'y',
                            'datasets' => [
                                [
                                    'data' => array_values($main_chart), 
                                    'borderColor' =>  [
                                        '#fff',
                                        '#fff',
                                        '#fff'
                                    ],
                                    'borderWidth' => 3,
                                    'hoverBorderColor'=>["#999","#999","#999"],     
                                    'backgroundColor' => [
                                        'rgba(154,255,154)',
                                        '#FF9A9A',
                                    ],           
                                ]
                            ]
                        ],
                        'clientOptions' => [
                            'legend' => [
                                'display' => false,
                                'position' => 'bottom',
                                'labels' => [
                                    'fontSize' => 14,
                                    'fontColor' => "#425062",
                                ]
                            ],
                            'tooltips' => [
                                'enabled' => true,
                                'intersect' => true
                            ],
                            'hover' => [
                                'mode' => false
                            ],
                            'maintainAspectRatio' => false,
                        
                        ],
                        'plugins' => [
                            new \yii\web\JsExpression('
                            [{
                                afterDatasetsDraw: function(chart, easing) {
                                    var ctx = chart.ctx;
                                
                                    chart.data.datasets.forEach(function (dataset, i) {
                                        var meta = chart.getDatasetMeta(i);
                                        if (!meta.hidden) {
                                                meta.data.forEach(function(element, index) {
                                                // Draw the text in black, with the specified font
                                                ctx.fillStyle = "rgb(0, 0, 0)";
                                                var fontSize = 16;
                                                var fontStyle = "normal";
                                                var fontFamily = "Helvetica";
                                                ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                                                // Just naively convert to string for now
                                                var dataString = dataset.data[index].toString()+"%";
                                                // Make sure alignment settings are correct
                                                ctx.textAlign = "center";
                                                ctx.textBaseline = "middle";
                                                var padding = 5;
                                                var position = element.tooltipPosition();
                                                ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                                            });
                                        }
                                    });
                                }
                            }],'
                            ),
                        ]
                    ]);      
                ?>
            </div>   
            <div class="w-100"></div>
            <div class="col mb-3">
                <?= 
                    ChartJs::widget([
                        'type' => 'doughnut',
                        'id' => 'structurePie',
                        'options' => [
                            'height' => 200,
                            'width' => 400,
                        ],
                        'data' => [
                            'radius' =>  "90%",
                            'labels' => array_keys($cat_chart),
                            'datasets' => [
                                [
                                    'data' => array_values($cat_chart), 
                                    'borderColor' =>  [
                                        '#fff',
                                        '#fff',
                                        '#fff'
                                    ],
                                    'borderWidth' => 3,
                                    'hoverBorderColor'=>["#999","#999","#999"],     
                                    'backgroundColor' => [
                                        '#ADC3FF',
                                        '#FF9A9A',
                                        'rgba(190, 124, 145, 0.8)',
                                        'rgba(154,255,154)'
                                    ],           
                                ]
                            ]
                        ],
                        'plugins' =>
                        new \yii\web\JsExpression('
                        [{
                            afterDatasetsDraw: function(chart, easing) {
                                var ctx = chart.ctx;
                                chart.data.datasets.forEach(function (dataset, i) {
                                    var meta = chart.getDatasetMeta(i);
                                    if (!meta.hidden) {
                                        meta.data.forEach(function(element, index) {
                                            // Draw the text in black, with the specified font
                                            ctx.fillStyle = "rgb(0, 0, 0)";
                                        
                                            var fontSize = 16;
                                            var fontStyle = "normal";
                                            var fontFamily = "Helvetica";
                                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                                        
                                            // Just naively convert to string for now
                                            var dataString = dataset.data[index].toString()+"%";
                                        
                                            // Make sure alignment settings are correct
                                            ctx.textAlign = "center";
                                            ctx.textBaseline = "middle";
                                        
                                            var padding = 5;
                                            var position = element.tooltipPosition();
                                            ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                                        });
                                    }
                                });
                            }
                        }]')
                    ]);    
                ?>
            </div>
        <?endif;?>
        <div class="w-100"></div>
        <div class="col-6 mb-3">
            <a href="/transaction/create"><button type="button" class="btn btn-primary btn-btn btn-block"><?= Yii::t('backend', 'Create Transaction') ?></button></a>
        </div>
        <div class="col-6 mb-3">
            <a href="/transaction/"><button type="button" class="btn btn-secondary btn-btn btn-block"><?= Yii::t('backend', 'Show all transactions') ?></button></a>
        </div>
        <div class="w-100"></div>
        <div class="col mb-3">
            <?=
                ListView::widget([
                    'dataProvider' => $AccountdataProvider,
                    'itemView' => '_list',
                ]);
            ?>
        </div>
        <div class="w-100"></div>
        <div class="col mb-3">
            <?= 
                GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'rowOptions'=> function($model){
                    if($model->type == 'Income'){
                        return ['style' => 'background-color: #e3fbe3;'];
                    }  
                    elseif($model->type == 'Expense') {
                        return ['style' => 'background-color: #fbe3e3;'];
                    }
                    else{
                        return ['style' => 'background-color: #efe3fb;'];
                    }
                },
                'columns' => [
                    ['class' => 'yii\grid\serialColumn'],
                    'amount_from',
                    'amount_to',
                    [
                        'attribute' => 'id_category',
                        'value' => function($model){
                            return Category::find()->where(['id'=> $model->id_category])->one()->name;
                        },   
                    ],
                    [
                        'attribute' => 'id_counterparty',
                        'value' => function($model){
                            return Counterparty::find()->where(['id'=> $model->id_counterparty])->one()->name;
                        },   
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, Transaction $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
                ]);
            ?>
        </div>
    </div>
</div>

