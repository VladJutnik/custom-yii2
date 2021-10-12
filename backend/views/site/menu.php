<?php


use yii\bootstrap4\Html;
?>
    <style>

        .btn {
            color: #000000 !important;
            font-size: 18px !important;
            font-family: Times, Times New Roman, serif  !important;
            font-weight: 600 !important;
        }
    </style>
<?php
echo Html::a('Список пациентов на сегодня', ['/site/personal-account'], ['data-method' => 'post', 'class' => 'btn btn-outline-success btn-block']);
echo Html::a('Список пациентов за период', ['/site/kol-osmotr'], ['data-method' => 'post', 'class' => 'btn btn-outline-success btn-block']);
echo Html::a('Список пациентов по месяцам', ['/site/calendar?ym='.date('Y-m')], ['data-method' => 'post', 'class' => 'btn btn-outline-success btn-block']);
echo Html::a('Количество осмотров', ['/site/osmotr'], ['data-method' => 'post', 'class' => 'btn btn-outline-success btn-block']);
//echo Html::a('Ттехническая поддержка', ['/messages/create?id='.Yii::$app->user->id], ['data-method' => 'post', 'class' => 'btn btn-outline-success btn-block']);



