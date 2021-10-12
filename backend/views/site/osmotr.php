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
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?= $this->render('menu') ?>
        </div>
        <div class="col-9">
            <div class="container">
               <!-- <div class="alert alert-success" role="alert">
                    Функционал работает только для врачей! Для остальных Функционал по прежнему в РАЗРАБОТКЕ!
                </div>-->
                <div class="text-center"><h5>Данные за проведенные осмотры</h5></div>
                <?php $form = ActiveForm::begin(); ?>

                <?php $two_column = ['options' => ['class' => 'row mt-3'], 'labelOptions' => ['class' => 'col-3 col-form-label font-weight-bold']]; ?>

              <!--  <?/*if(Yii::$app->user->identity->post == 'admin' || Yii::$app->user->can('gldoctor')){*/?>
                    <?/*= $form->field($model, 'organization_id', $two_column)->dropDownList($organization_item,
                        [
                            'options' => [$organ => ['Selected' => true]],
                            'class' => 'form-control col-9'
                        ])*/?>
                    <?/*= $form->field($model, 'card_number', $two_column)->dropDownList($users_items,
                        [
                            'options' => [$users_b => ['Selected' => true]],
                            'class' => 'form-control col-9'
                        ])->label('Специалист')*/?>
                    --><?/*}*/?>
                <?if(Yii::$app->user->identity->post != 'физио'){
                    if(Yii::$app->user->identity->post != 'nurse'){
                        echo $form->field($model, 'address_overall', $two_column)->dropDownList($items2,
                            [
                                'options' => [$address_overall => ['Selected' => true]],
                                'class' => 'form-control col-9'
                            ])->label('Результат медосмотра');
                    }
                }?>

                <?= $form->field($model, 'experience2', $two_column)->textInput(
                    [
                        //'options' => [$items => ['Selected' => true]],
                        'autocomplete' => 'off',
                        'value' => $s,
                        'class' => 'form-control datepicker-here col-9'
                    ])->label('С') ?>

                <?= $form->field($model, 'experience3', $two_column)->textInput(
                    [
                        //'options' => [$items => ['Selected' => true]],
                        'autocomplete' => 'off',
                        'value' => $po,
                        'class' => 'form-control datepicker-here col-9'
                    ])->label('По') ?>

                <?= $form->field($model, 'job', $two_column)->hiddenInput(
                    [
                        //'options' => [$items => ['Selected' => true]],
                        'value' => '1',
                    ])->label(false) ?>

                <div class="form-group row">
                    <?= Html::submitButton('Показать', ['name' => 'identificator', 'value' => 'show', 'class' => 'btn main-button-3 form-control mt-3 col-12 identificator']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <?if($statusww){
        if (Yii::$app->user->identity->post != 'физио' && Yii::$app->user->identity->post != 'nurse' ){?>
            <?
        $table = '';
        $table2 = '';
        $table3 = '';
        $num_list = 0;
        $num_ne_v = 0;
        $num_v = 0;
        $num_ost = 0;
        //print_r($address_overall);
        if ($patients != ''){
            foreach ($patients as $patient){
                if($patient['contraindications'] == '0'){
                    /* if($doc == 'nt'){

                     }*/
                    $table .= '
                           <tr>
                               
                               <td>'.Html::a("<b>Просмотр</b>", ['list-patients/view?id='.$patient['user_id']], ['class' => 'btn btn-sm btn-success']).'</td>
                               <td>'.$model->get_fio($patient['user_id']).'</td>
                               <td>'.$model->get_type6($patient['contraindications']).'</td>
                               <td>'.$model->get_data($patient['user_id']).'</td>';
                    if(Yii::$app->user->identity->post != 'gldoctor'){
                        $table .= '<td>'.$patient['doctor'].'</td>';
                    }
                    $table .= '</tr>';
                    $num_list++;
                    $num_ne_v++;
                }
                elseif($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4'){
                    $table2 .= '
                           <tr>
                               
                               <td>'.Html::a("<b>Просмотр</b>", ['list-patients/view?id='.$patient['user_id']], ['class' => 'btn btn-sm btn-success']).'</td>
                               <td>'.$model->get_fio($patient['user_id']).'</td>
                               <td>'.$model->get_type6($patient['contraindications']).'</td>
                               <td>'.$model->get_data($patient['user_id']).'</td>';
                    if(Yii::$app->user->identity->post != 'gldoctor'){
                        $table2 .= '<td>'.$patient['doctor'].'</td>';
                    }
                    $table2 .= '</tr>';
                    $num_list++;
                    $num_v++;
                }
                elseif($patient['contraindications'] == ''){

                    $table3 .= '
                           <tr>
                               
                               <td>'.Html::a("<b>Просмотр</b>", ['list-patients/view?id='.$patient['user_id']], ['class' => 'btn btn-sm btn-success']).'</td>
                               <td>'.$model->get_fio($patient['user_id']).'</td>
                               <td>'.$model->get_type6($patient['contraindications']).'</td>
                               <td>'.$model->get_data($patient['user_id']).'</td>';
                    if(Yii::$app->user->identity->post != 'gldoctor'){
                        $table3 .= '<td>'.$patient['doctor'].'</td>';
                    }
                    $table3 .= '</tr>';
                    $num_list++;
                    $num_ost++;
                }
            }
        }
        ?>
            <br>
            <h5 class="text-center">Результаты проведенных осмотров <?=$doc?></h5>
            <div class="container">
            <table class=" table-bordered table-sm mt-3">
                <tr>
                    <td align="left"><b>Количество проведенных осмотров всего</b></td>
                    <td><?=$num_list?></td>
                </tr>
                <?if($address_overall == '1' || $address_overall == '3'){?>
                    <tr>
                        <td align="left"><b>Количество выявленных противопоказаний</b></td>
                        <td><?=$num_v?></td>
                    </tr>
                <?}?>
                <?if($address_overall == '2' || $address_overall == '3'){?>
                    <tr>
                        <td align="right"><b>Количество не выявленных противопоказаний</b></td>
                        <td><?=$num_ne_v?></td>
                    </tr>
                <?}?>
                <?if($address_overall == '4' || $address_overall == '3'){?>
                    <tr>
                        <td align="left"><b>Количество не проведенных осмотров</b></td>
                        <td><?=$num_ost?></td>
                    </tr>
                <?}?>
            </table>
        </div><br>
            <div class="row">
            <div class="col-2">
                <input type="button" id="vei" class="btn btn-primary form-control beforeload" value="Показать список ФИО">
                <input type="button" id="load" class="btn btn-primary form-control" style="display: none" value="Скрыть список ФИО">
            </div>
            <div class="col-10">
                <div class="list" style="display:none;">
                    <input type="button" class="btn btn-warning table2excel mb-1 "
                           title="Вы можете скачать в формате Excel" value="Скачать в Excel" id="pechat222">
                    <table id="tableId" class="table table-bordered table-sm table2excel_with_colors">
                        <tr>
                            <!--<th class="text-center">№</th>-->
                            <!--<th class="text-center">user_id</th>-->
                            <th class="text-center">Данные</th>
                            <th class="text-center">ФИО</th>
                            <th class="text-center">Результат</th>
                            <th class="text-center">Дата посещения</th>
                            <th class="text-center">Принимавщий доктор</th>
                        </tr>
                        <?
                        //'3' => 'Все',
                        //'1' => 'Выявлены противопоказания',
                        //'2' => 'Не выявлены противопоказания',
                        //'4' => 'Осмотр не проведен',
                        if($address_overall == '3'){
                            echo $table;
                            echo $table2;
                            echo $table3;
                        }
                        elseif($address_overall == '1'){
                            echo $table2;
                        }
                        elseif($address_overall == '2'){
                            echo $table;
                        }
                        elseif($address_overall == '4'){
                            echo $table3;
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <?}
        if (Yii::$app->user->identity->post == 'физио'){?>
            <br>
            <h5 class="text-center">Результаты проведенных исследований </h5>
            <div class="container">
            <table class=" table-bordered table-sm mt-3">
                <tr>
                    <td align="center"><b>Тип иследования</b></td>
                    <td align="center"><b>Количество проведений</b></td>
                </tr>
              <?=$str_fisio?>
            </table>
        </div>
        <?}
        if (Yii::$app->user->identity->post == 'nurse'){?>

            <br>
            <h5 class="text-center">Результаты проведенных анализов </h5>
            <div class="container">
            <table class=" table-bordered table-sm mt-3">
                <tr>
                    <td align="center"><b>Тип иследования</b></td>
                    <td align="center"><b>Количество проведений</b></td>
                </tr>
              <?=$str_fisio?>
            </table>
        </div>
        <?}?>
    <?}?>

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