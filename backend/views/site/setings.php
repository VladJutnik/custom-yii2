<?php

use common\models\Factors;
use common\models\Therapist;
use phpnt\chartJS\ChartJs;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\widgets\DetailView;
use yii\bootstrap4\Widget;

/* @var $this yii\web\View */
/* @var $model common\models\ListPatients */

$this->title = 'Настройка программы';
$this->params['breadcrumbs'][] = ['label' => 'List Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<?php //print_r($model) ?>
<?php
$two_column = ['options' => ['class' => 'row justify-content-center mt-3'], 'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']];

$form = ActiveForm::begin(); ?>
    <div class="container">
        <h5 class="text-center mt-4 mb-3">Настройка программы:</h5>
        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'name', [
                    'options' => ['class' => 'row mt-2 mr-1'],
                    'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
                ])->textarea([
                    'rows' => 2,
                    'class' => 'form-control col-8',
                ]) ?>
                <?= $form->field($model, 'short_name', [
                    'options' => ['class' => 'row mt-2 mr-1'],
                    'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
                ])->textarea([
                    'rows' => 2,
                    'class' => 'form-control col-8',
                ]) ?>
                <?= $form->field($model, 'address', [
                    'options' => ['class' => 'row mt-2 mr-1'],
                    'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
                ])->textarea([
                    'rows' => 2,
                    'class' => 'form-control col-8',
                ]) ?>
                <?= $form->field($model, 'telephone', [
                    'options' => ['class' => 'row mt-2 mr-1'],
                    'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
                ])->textInput([
                    'class' => 'form-control col-8',
                ]) ?>
                <?= $form->field($model, 'fax_number', [
                    'options' => ['class' => 'row mt-2 mr-1'],
                    'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
                ])->textInput([
                    'class' => 'form-control col-8',
                ]) ?>
                <?= $form->field($model, 'licenses', [
                    'options' => ['class' => 'row mt-2 mr-1'],
                    'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'form-control col-8',
                ]) ?>
                <?= $form->field($model, 'ogrn_code', [
                    'options' => ['class' => 'row mt-2 mr-1'],
                    'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
                ])->textInput([
                    'type' => 'number',
                    'maxlength' => true,
                    'class' => 'form-control col-8',
                ]) ?>
                <?= $form->field($model, 'inn', [
                    'options' => ['class' => 'row mt-2 mr-1'],
                    'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'form-control col-8',
                ]) ?>
                <?= $form->field($model, 'mail', [
                    'options' => ['class' => 'row mt-2 mr-1'],
                    'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'form-control col-8',
                ]) ?>
                <? $items_accounting = [
                    '1' => 'длинная версия',
                    '0' => 'короткая версия'
                ]; ?>
                <?= $form->field($model, 'version', [
                    'options' => ['class' => 'row mt-2 mr-1'],
                    'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
                ])->dropDownList($items_accounting, [
                    'maxlength' => true,
                    'class' => 'form-control col-8'
                ]) ?>
            </div>
            <div class="col-6">
                <div class="vrach">
                    <?
                    if (!empty($model->therapist))
                    {
                        echo $form->field($model, 'therapist')->checkbox(['value' => '1', 'checked ' => true]);
                    }
                    else
                    {
                        echo $form->field($model, 'therapist')->checkbox();
                    } ?>
                    <?
                    if (!empty($model->neurologist))
                    {
                        echo $form->field($model, 'neurologist')->checkbox(['value' => '1', 'checked ' => true]);
                    }
                    else
                    {
                        echo $form->field($model, 'neurologist')->checkbox();
                    } ?>
                    <?
                    if (!empty($model->ophthalmologist))
                    {
                        echo $form->field($model, 'ophthalmologist')->checkbox(['value' => '1', 'checked ' => true]);
                    }
                    else
                    {
                        echo $form->field($model, 'ophthalmologist')->checkbox();
                    } ?>
                    <?
                    if (!empty($model->surdologist))
                    {
                        echo $form->field($model, 'surdologist')->checkbox(['value' => '1', 'checked ' => true]);
                    }
                    else
                    {
                        echo $form->field($model, 'surdologist')->checkbox();
                    } ?>
                    <?
                    if (!empty($model->psychiatrist))
                    {
                        echo $form->field($model, 'psychiatrist')->checkbox(['value' => '1', 'checked ' => true]);
                    }
                    else
                    {
                        echo $form->field($model, 'psychiatrist')->checkbox();
                    } ?>
                    <?
                    if (!empty($model->narcologist))
                    {
                        echo $form->field($model, 'narcologist')->checkbox(['value' => '1', 'checked ' => true]);
                    }
                    else
                    {
                        echo $form->field($model, 'narcologist')->checkbox();
                    } ?>
                    <?
                    if (!empty($model->gynecologist))
                    {
                        echo $form->field($model, 'gynecologist')->checkbox(['value' => '1', 'checked ' => true]);
                    }
                    else
                    {
                        echo $form->field($model, 'gynecologist')->checkbox();
                    } ?>
                    <?
                    if (!empty($model->surgeon))
                    {
                        echo $form->field($model, 'surgeon')->checkbox(['value' => '1', 'checked ' => true]);
                    }
                    else
                    {
                        echo $form->field($model, 'surgeon')->checkbox();
                    } ?>
                    <?
                    if (!empty($model->dermatovenerologist))
                    {
                        echo $form->field($model, 'dermatovenerologist')->checkbox(['value' => '1', 'checked ' => true]);
                    }
                    else
                    {
                        echo $form->field($model, 'dermatovenerologist')->checkbox();
                    } ?>
                    <?
                    if (!empty($model->dentist))
                    {
                        echo $form->field($model, 'dentist')->checkbox(['value' => '1', 'checked ' => true]);
                    }
                    else
                    {
                        echo $form->field($model, 'dentist')->checkbox();
                    } ?>
                </div>
            </div>
        </div>
        <?
        if (Yii::$app->user->can('admin'))
        { ?>
            <div class="form-group text-center">
                <?= Html::submitButton('Сохранить', ['class' => 'btn mt-3 btn-success ']) ?>
            </div>
        <? } ?>
    </div>
<?php ActiveForm::end(); ?>

<!--/*
$dataWeatherOne = [
    'labels' => ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
    'datasets' => [
        [
            'data' => [-14, -10, -4, 6, 17, 23, 22, 22, 13, 2, -5, -12],
            'label' => "Линейный график (tºC Урал).",
            'fill' => false,
            'lineTension' => 0.1,
            'backgroundColor' => "rgba(75,192,192,0.4)",
            'borderColor' => "rgba(75,192,192,1)",
            'borderCapStyle' => 'butt',
            'borderDash' => [],
            'borderDashOffset' => 0.0,
            'borderJoinStyle' => 'miter',
            'pointBorderColor' => "rgba(75,192,192,1)",
            'pointBackgroundColor' => "#fff",
            'pointBorderWidth' => 1,
            'pointHoverRadius' => 5,
            'pointHoverBackgroundColor' => "rgba(75,192,192,1)",
            'pointHoverBorderColor' => "rgba(220,220,220,1)",
            'pointHoverBorderWidth' => 2,
            'pointRadius' => 1,
            'pointHitRadius' => 10,
            'spanGaps' => false,
        ]
    ]
];
$dataWeatherTwo = [
    'labels' => ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
    'datasets' => [
        [
            'data' => [-14, -10, -4, 6, 17, 23, 22, 22, 13, 2, -5, -12],
            'label' => "График (tºC Урал).",
            'fill' => true,
            'lineTension' => 0.1,
            'backgroundColor' => "rgba(75,192,192,0.4)",
            'borderColor' => "rgba(75,192,192,1)",
            'borderCapStyle' => 'butt',
            'borderDash' => [],
            'borderDashOffset' => 0.0,
            'borderJoinStyle' => 'miter',
            'pointBorderColor' => "rgba(75,192,192,1)",
            'pointBackgroundColor' => "#fff",
            'pointBorderWidth' => 1,
            'pointHoverRadius' => 5,
            'pointHoverBackgroundColor' => "rgba(75,192,192,1)",
            'pointHoverBorderColor' => "rgba(220,220,220,1)",
            'pointHoverBorderWidth' => 2,
            'pointRadius' => 1,
            'pointHitRadius' => 10,
            'spanGaps' => false,
        ],
            [
                'data' => [8, 10, 11, 15, 21, 26, 28, 30, 26, 21, 16, 9],
                'label' => "График (tºC Сочи).",
                'fill' => true,
                'lineTension' => 0.1,
                'backgroundColor' => "rgba(255, 234, 0,0.4)",
                'borderColor' => "rgba(255, 234, 0,1)",
                'borderCapStyle' => 'butt',
                'borderDash' => [],
                'borderDashOffset' => 0.0,
                'borderJoinStyle' => 'miter',
                'pointBorderColor' => "rgba(255, 234, 0,1)",
                'pointBackgroundColor' => "#fff",
                'pointBorderWidth' => 1,
                'pointHoverRadius' => 5,
                'pointHoverBackgroundColor' => "rgba(255, 234, 0,1)",
                'pointHoverBorderColor' => "rgba(220,220,220,1)",
                'pointHoverBorderWidth' => 2,
                'pointRadius' => 1,
                'pointHitRadius' => 10,
                'spanGaps' =>false,
            ]
        ]
];
$dataScatter = [
    'datasets' => [
        [
            'data' => [
                [
                    'x' => -10,
                    'y' => 0
                ], [
                    'x' => 0,
                    'y' => 10
                ], [
                    'x' => 10,
                    'y' => 5
                ],
            ],
            'label' => 'График рассеивания',
            'fill' => true,
            'lineTension' => 0.1,
            'backgroundColor' => "rgba(75,192,192,0.4)",
            'borderColor' => "rgba(75,192,192,1)",
            'borderCapStyle' => 'butt',
            'borderDash' => [],
            'borderDashOffset' => 0.0,
            'borderJoinStyle' => 'miter',
            'pointBorderColor' => "rgba(75,192,192,1)",
            'pointBackgroundColor' => "#fff",
            'pointBorderWidth' => 1,
            'pointHoverRadius' => 5,
            'pointHoverBackgroundColor' => "rgba(75,192,192,1)",
            'pointHoverBorderColor' => "rgba(220,220,220,1)",
            'pointHoverBorderWidth' => 2,
            'pointRadius' => 1,
            'pointHitRadius' => 10,
            'spanGaps' => false,
        ]
    ]
];

$dataPie = [
    'labels' => [
        "Красный",
        "Синий",
        "Желтый"
    ],
    'datasets' => [
        [
            'data' => [300, 50, 100],
             'backgroundColor' => [
                     "#FF6384",
                     "#36A2EB",
                     "#FFCE56"
        ],
        'hoverBackgroundColor' => [
        "#FF6384",
        "#36A2EB",
        "#FFCE56"
        ]
    ]
    ]
];

$dataBubble = [
    'datasets' => [
        [
            'label' => 'Пузырьковый график',
            'data' => [
                    [
                        'x' => 20,
                        'y' => 30,
                        'r' => 15
                    ],
                    [
                        'x' => 40,
                        'y' => 10,
                        'r' => 10
                    ],
            ],
            'backgroundColor' =>"#FF6384",
            'hoverBackgroundColor' => "#FF6384",
        ]
    ]
];*/

// вывод графиков-->

    <!--<div class="container">

/*        echo ChartJs::widget([
            'type' => ChartJs::TYPE_LINE,
            'data' => $dataWeatherOne,
            'options' => []
        ]);
        echo '<br><br>';
        echo ChartJs::widget([
            'type' => ChartJs::TYPE_BAR,
            'data' =>  $dataWeatherTwo,
            'options' => []
        ]);
        echo '<br><br>';
        echo ChartJs::widget([
            'type' => ChartJs::TYPE_RADAR,
            'data' => $dataWeatherOne,
            'options' => []
        ]);
        echo '<br><br>';
        echo ChartJs::widget([
            'type' => ChartJs::TYPE_RADAR,
            'data' => $dataWeatherTwo,
            'options' => []
        ]);
        echo '<br><br>';
        echo ChartJs::widget([
            'type' => ChartJs::TYPE_POLAR_AREA,
            'data' => $dataWeatherOne,
            'options' => []
        ]);
        echo '<br><br>';
        echo ChartJs::widget([
            'type' => ChartJs::TYPE_POLAR_AREA,
            'data' => $dataWeatherTwo,
            'options' => []
        ]);
        echo '<br><br>';
        echo ChartJs::widget([
            'type' => ChartJs::TYPE_PIE,
            'data' => $dataPie,
            'options' => []
        ]);
        echo '<br><br>';
        echo ChartJs::widget([
            'type' => ChartJs::TYPE_DOUGHNUT,
            'data' => $dataPie,
            'options' => []
        ]);
        echo '<br><br>';
        echo ChartJs::widget([
            'type' => ChartJs::TYPE_BUBBLE,
            'data' => $dataBubble,
            'options' => []
        ]);
        */
    </div>-->


<?
$script = <<< JS
   
    var field_3 = $('#setings-version');
    field_3.on('change', function () {
           if (field_3.val() === "0" ) {
               $('.vrach').show();
           }
           else{
              $('.vrach').hide();
           }
    });
    field_3.trigger('change');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>