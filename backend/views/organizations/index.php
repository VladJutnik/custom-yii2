<?php

use common\models\ListPatients;
use kartik\switchinput\SwitchInput;
use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список организаций';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="organization-index">
    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <h4 class="mb-0 mt-2 ml-3"><?= Html::encode($this->title) ?></h4>
                    <?= Html::a('Добавить новую организацию', ['create'], ['class' => 'btn btn-primary ml-3 m-1 px-5 btn-sm']) ?>
                </div>
            </div>
            <hr/>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'options' => [
                    'class' => 'menus-table table-responsive'],
                'tableOptions' => [
                    'class' => 'table table-bordered table-responsive'
                ],
                /*'rowOptions' => ['class' => 'grid_table_tr'],*/
                'rowOptions' => function($model) {
                    //Проверяем включена ли организация если что подсвечиваем
                    if($model->status_veiws == 1) {
                        return ['style' => 'background-color:#5f9ea0;'];
                    }
                    else{
                        return ['style' => 'background-color:#ed9274;'];
                    }
                },
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'title',
                    'federal_district_id',
                    'region_id',
                    'municipality_id',
                    'address',
                    'phone',
                    [
                        //'attribute' => 'organization_id',
                        'value' => function ($model) {
                            /*return  date('Y-m-d', strtotime($model->get_date($model->end_date_medical_examination)));*/
                            //return $model->get_date($model->end_date_medical_examination);
                            return '';
                        },
                        'header' => 'Количество филиалов',
                        'headerOptions' => ['class' => 'grid_table_th'],
                        'contentOptions' => ['class' => ''],
                    ],
                    [
                        'header' => 'Управление',
                        'class' => 'yii\grid\ActionColumn',
                        //'template' => ' {view2} {view3} {view} {update} {exportk} {planned-cost} {fact-cost} {export-day} {export-day2} {delete}',
                        'template' => '{onoff} {view} {update} {view-madal}',
                        'contentOptions' => ['class' => 'action-column text-center'],
                        'buttons' => [
                            'onoff' => function ($url, $model, $key) {
                                //Даем возможность изменять статус только админам
                                if(Yii::$app->user->can('admin_organizations') || Yii::$app->user->can('admin')){
                                    if($model->status_veiws == 0 || $model->status_veiws == '') {
                                        $value = 0;
                                    }else{
                                        $value = 1;
                                    }
                                    return SwitchInput::widget(
                                        [
                                            'name' => 'status_veiws',
                                            'options' => [ 'data-id' => $model->id ],
                                            'type' => SwitchInput::CHECKBOX,
                                            'value' => $value,
                                            'pluginOptions' => [
                                                'size' => 'mini',
                                                'animate' => false,
                                                'onText' => 'Вкл',
                                                'offText' => 'Выкл'
                                            ],
                                            'pluginEvents' => [
                                                "switchChange.bootstrapSwitch" => "
                                                    function (event) {
                                                    var status_veiws = 0; 
                                                    if(jQuery(this).is(':checked')){status_veiws = 1;} 
                                                    var id = jQuery(this).attr('data-id');
                                                
                                                    jQuery.ajax({url:'/organizations/onoff?id='+id+'&status_veiws='+status_veiws,
                                                    success:function(model){console.log(model)}})}" ,
                                            ],
                                        ]
                                    );
                                }
                            },
                            'view' => function ($url, $model, $key) {

                                return Html::a('Просмотр', $url, [
                                    'title' => Yii::t('yii', 'Просмотр информации об организации'),
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-sm btn-success btn-block'
                                ]);

                            },
                            'update' => function ($url, $model, $key) {
                                return Html::a('Редактирование', $url, [
                                    'title' => Yii::t('yii', 'Редактировать информацию об организации'),
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-sm btn-primary btn-block'
                                ]);
                            },
                            'view-madal' => function ($url, $model, $key) {
                                if ($model->status_print == '')
                                {
                                    return
                                        Html::button('Модфльное окно с выбором', [
                                            'data_id' => $model->id,
                                            'class' => 'btn btn-sm btn-warning btn-block',
                                            'onclick' => '
                                            $.get("view-madal?id=" + $(this).attr("data_id"), function(data){
                                            $("#showModal .modal-body").empty();
                                            $("#showModal .modal-body").append(data);
                                            //console.log(data);
                                            $("#showModal").modal("show");
                                        });'
                                        ]);
                                }
                                else{
                                    if($model->name_act != ''){
                                        return '<a class="btn btn-sm main-button-2 btn-block"
                                        target="_blank"
                                        href="../act/'.$model->name_act.'" title="Скачать"
                                        data-toggle="tooltip"> Скачать заключительный акт (последняя сохраненная версия) <span
                                        class="glyphicon glyphicon-arrow-down"></span></a>
                                ';
                                    }
                                }
                            },
                            /*'view-akt' => function ($url, $model, $key) {
                                if ($model->status_print == '')
                                {
                                    return
                                        Html::button('Заключительный акт', [
                                            'data_id' => $model->id,
                                            'class' => 'btn btn-sm btn-warning btn-block',
                                            'onclick' => '
                                        $.get("view-akt?id=" + $(this).attr("data_id"), function(data){
                                        $("#showModal .modal-body").empty();
                                        $("#showModal .modal-body").append(data);
                                        //console.log(data);
                                        $("#showModal").modal("show");
                                    });'
                                        ]);
                                }
                                else{
                                    if($model->name_act != ''){
                                        return '<a class="btn btn-sm main-button-2 btn-block"
                                        target="_blank"
                                        href="../act/'.$model->name_act.'" title="Скачать"
                                        data-toggle="tooltip"> Скачать заключительный акт (последняя сохраненная версия) <span
                                        class="glyphicon glyphicon-arrow-down"></span></a>
                                ';
                                    }
                                }
                            },*/
                        ],
                    ]

                ],

            ]); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Печать документов по организации</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">	<span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">

            </div>
        </div>
    </div>
</div>