<?php


use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
$this->title = 'Данные за проведенные осмотры';
$items2 = [
    '3' => 'Все',
    '1' => 'Выявлены противопоказания',
    '2' => 'Не выявлены противопоказания',
    '4' => 'Осмотр не проведен',
];

$list = 1;
//$organization_null = array('0' => 'Выберите ...');
/*$users = \common\models\User::find()->where(['!=', 'post', 'admin'])->andWhere(['!=', 'post', 'school'])->all();
$users_items = ArrayHelper::map($users, 'id', 'name');*/
//Yii::$app->user->can('admin')
//print_r($users_items)

$month_name = [
    '01'=>'Январь',
    '02'=>'Февраль',
    '03'=>'Март',
    '04'=>'Апрель',
    '05'=>'Май',
    '06'=>'Июнь',
    '07'=>'Июль',
    '08'=>'Август',
    '09'=>'Сентябрь',
    '10'=>'Октябрь',
    '11'=>'Ноябрь',
    '12'=>'Декабрь',
];
?>
    <style>
        .table2 {
            width: 100% !important;
            border: none !important;
        }
        .table2 td {
            height: 100px;
            border: 2px solid black;
            padding-top: 0px !important;
        }
        .table2 th {
            font-weight: bold;
            text-align: center;
            padding: 10px 15px;
            background: #d8d8d8;
            font-size: 14px;
            max-width: 40px;
            height: 30px;
            border: 2px solid black;
        }
        .today {
            background: #6c76d9;
        }
        .text-td {
            font-size: large;
            font-weight: bold;
            padding-left: 10px;
        }
        .table2 td:nth-of-type(7) {
            border-right: none;
        }
        .table2 td:nth-of-type(1) {
            border-left: none;
        }
        .table2 th:nth-of-type(6) {
            color: blue;
        }
        .table2 th:nth-of-type(7) {
            border-right: none !important;
            color: blue;
        }
        .table2 th:nth-of-type(1) {
            border-left: none !important;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-3">
                <?= $this->render('menu') ?>
            </div>
            <div class="col-9">
                <div class="container">
                    <h5 class="text-center">
                        <?=Html::a('Предыдущий месяц', ['/site/calendar?ym='.$prev], ['data-method' => 'post', 'class' => 'btn btn-outline-success btn-sm']); ?>
                        Данные за <?=$month_name[$month].' '.$year?> года принято всего - <?=$count_patient_v?> человек
                        <?=Html::a('Следующий месяц', ['/site/calendar?ym='.$next], ['data-method' => 'post', 'class' => 'btn btn-outline-success btn-sm']); ?>
                    </h5>
                    <table class="table2 ">
                        <tr>

                            <th>понедельник</th>
                            <th>вторник</th>
                            <th>среда</th>
                            <th>четверг</th>
                            <th>пятница</th>
                            <th>суббота</th>
                            <th>воскресение</th>
                        </tr>
                        <?php
                        foreach ($weeks as $week) {
                            echo $week;
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?
$script = <<< JS
       
    $('#vei').on('click', function () {
        //это див 
        $(".list").css('display', 'block');
        //это кнопки
        $("#load").css('display', 'block');
        $("#vei").css('display', 'none');
    });

    $('#load').on('click', function () {
        $(".list").css('display', 'none');
        //это кнопки
        $("#load").css('display', 'none');
        $("#vei").css('display', 'block');
    });
    
    $("#pechat222").click(function () {
    var table = $('#tableId');
        if (table && table.length) {
            var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
            $(table).table2excel({
                exclude: ".noExl",
                name: "Excel Document Name",
                filename: "Энергетическая ценность рациона.xls",
                fileext: ".xls",
                exclude_img: true,
                exclude_links: true,
                exclude_inputs: true,
                preserveColors: preserveColors
            });
        }
    });
JS;
$this->registerJs($script, yii\web\View::POS_READY);