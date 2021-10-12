<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Раздел пользователей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать пользователя', ['craete-user'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'name',
            [
                'attribute' => 'Роль в программе',
                'value' => function($model){
                    return $model->get_role($model->id);
                },
            ],
            'email:email',
            'login',
            //'organization_id',

            [
                'class' => 'yii\grid\ActionColumn',
                //'template' => '',
                'template' => '{login} {view} {update} ',
                'contentOptions' => ['class' => 'action-column'],
                'buttons' => [
                    'login' => function ($url, $model, $key) {
                        if(Yii::$app->user->can('gldoctor') || Yii::$app->user->can('admin')){
                            return Html::a('<span class="glyphicon glyphicon-log-in"></span>', $url, [
                                'title' => 'login',
                                'data-toggle'=>'tooltip',
                                'class'=>'btn btn-sm btn-primary',
                            ]);
                        }
                    },

                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('yii', 'Просмотр'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-success'
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Редактировать'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-primary'
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        if(Yii::$app->user->identity->id == $model->id) {
                            return null;
                        }
                        else{
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Удалить'),
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-sm btn-danger',
                                'data' => ['confirm' => 'Вы уверены что хотите удалить пользователя?'],
                            ]);
                        }
                    },
                ],
            ]

        ],
    ]); ?>


</div>
