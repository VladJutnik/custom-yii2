<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Муниципальные образования';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="municipality-index">
    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <h4 class="mb-0 mt-2 ml-3"><?= Html::encode($this->title) ?></h4>
                    <?= Html::a('Добавить новое муниципальное образование', ['create'], ['class' => 'btn btn-primary ml-3 m-1 px-5 btn-sm']) ?>
                </div>
            </div>
            <hr/>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'region_id',
                        'value' => function ($model) {
                            return Yii::$app->myComponent->getReg($model->region_id);
                        },
                        'headerOptions' => ['class' => 'grid_table_th'],
                        'contentOptions' => ['class' => ''],
                    ],
                    'name',
                    [
                        'header' => 'Увправление',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update}',
                        'contentOptions' => ['class' => 'action-column text-center'],
                        'buttons' => [

                            'view' => function ($url, $model, $key) {
                                return Html::a('<span class="lni lni-magnifier"></span>', $url, [
                                    'title' => Yii::t('yii', 'Посмотреть'),
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-outline-primary'
                                ]);
                            },
                            'update' => function ($url, $model, $key) {
                                return Html::a('<span class="lni lni-pencil-alt"></span>', $url, [
                                    'title' => Yii::t('yii', 'Редактировать'),
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-outline-primary'
                                ]);
                            },
                        ],
                    ]

                ],
            ]); ?>
        </div>
    </div>
</div>