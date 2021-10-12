<?php


use phpnt\chartJS\ChartJs;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Данные по проведенным осмотрам';
$items2 = [
    '3' => 'Все',
    '1' => 'Выявлены противопоказания',
    '2' => 'Не выявлены противопоказания',
    '4' => 'Осмотр не проведен',
];

$items22 = [
    'все' => 'Все',
    'терапевт' => 'Терапевт',
    'невролог' => 'Невролог',
    'офтальмолог' => 'Офтальмолог',
    'отоларинголог' => 'Отоларинголог',
    'нарколог' => 'Нарколог',
    'психиатр' => 'Психиатр',
    'хирург' => 'Хирург',
    'стоматолог' => 'Стоматолог',
    'дерматовенеролог' => 'Дерматовенеролог',
    'гиниколог' => 'Гинеколог',
];

$list = 1;
//$organization_null = array('0' => 'Выберите ...');
/*$users = \common\models\User::find()->where(['!=', 'post', 'admin'])->andWhere(['!=', 'post', 'school'])->all();
$users_items = ArrayHelper::map($users, 'id', 'name');*/
//Yii::$app->user->can('admin')
//print_r($users_items)
?>
    <div class="container-fluid">

        <div class="container">

            <div class="text-center"><h5>Данные за проведенные осмотры</h5></div>
            <?php $form = ActiveForm::begin(); ?>

            <?php $two_column = ['options' => ['class' => 'row mt-3'], 'labelOptions' => ['class' => 'col-3 col-form-label font-weight-bold']]; ?>

            <!--  <? /*if(Yii::$app->user->identity->post == 'admin' || Yii::$app->user->can('gldoctor')){*/ ?>
                    <? /*= $form->field($model, 'organization_id', $two_column)->dropDownList($organization_item,
                        [
                            'options' => [$organ => ['Selected' => true]],
                            'class' => 'form-control col-9'
                        ])*/ ?>
                    <? /*= $form->field($model, 'card_number', $two_column)->dropDownList($users_items,
                        [
                            'options' => [$users_b => ['Selected' => true]],
                            'class' => 'form-control col-9'
                        ])->label('Специалист')*/ ?>
                    --><? /*}*/ ?>
            <?= $form->field($model, 'card_number', $two_column)->dropDownList($items22,
                [
                    'options' => [$users_b => ['Selected' => true]],
                    'class' => 'form-control col-9'
                ])->label('Специалист') ?>
            <?= $form->field($model, 'address_overall', $two_column)->dropDownList($items2,
                [
                    'options' => [$address_overall => ['Selected' => true]],
                    'class' => 'form-control col-9'
                ])->label('Результат медосмотра') ?>

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
        <?

        if ($status == 1)
        { ?>
            <?
            $table_ter = '';
            $table_nev = '';
            $table_oft = '';
            $table_oto = '';
            $table_nor = '';
            $table_ph = '';
            $table_sur = '';
            $table_stom = '';
            $table_der = '';
            $table_gin = '';
            if ($patients_status == 3)
            {
                ?>
                <div class="alert alert-warning" role="alert">
                    Данных не найдено!
                </div>
            <?
            }
            elseif ($patients_status == 2 || $patients_status == 1)
            {
                if ($patients_ter != '')
                {
                    $num_list = 0;
                    $num_ne_v_ter = 0;
                    $num_v_ter = 0;
                    $num_ost_ter = 0;
                    foreach ($patients_ter as $patient)
                    {
                        if ($patient['contraindications'] == '0')
                        {
                            $num_list++;
                            $num_ne_v_ter++;
                        }
                        elseif ($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4')
                        {
                            $num_list++;
                            $num_v_ter++;
                        }
                        elseif ($patient['contraindications'] == '')
                        {
                            $num_list++;
                            $num_ost_ter++;
                        }
                    }
                    $table_ter .= '
                            <table class=" table-bordered table-sm mt-3">
                               <tr>
                                   <td colspan="2" align="center"><b>Осмотры Терапевта</b></td>
                               </tr> 
                               <tr>
                                   <td align="left"><b>Количество проведенных осмотров всего</b></td>
                                   <td>' . $num_list . '</td>
                               </tr>
                        ';
                    if ($address_overall == '1' || $address_overall == '3')
                    {
                        $table_ter .= '
                                <tr>
                                    <td align="left"><b>Количество выявленных противопоказаний</b></td>
                                    <td>' . $num_v_ter . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '2' || $address_overall == '3')
                    {
                        $table_ter .= '
                                <tr>
                                    <td align="left"><b>Количество не выявленных противопоказаний</b></td>
                                    <td>' . $num_ne_v_ter . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '4' || $address_overall == '3')
                    {
                        $table_ter .= '
                                <tr>
                                    <td align="left"><b>Количество не проведенных осмотров</b></td>
                                    <td>' . $num_ost_ter . '</td>
                                </tr>
                            ';
                    }
                    $table_ter .= '</table>';
                }
                else
                {
                    $table_ter .= '<b>Осмотров Терапевта не найдено!</b>';
                }
                if ($patients_nev != '')
                {
                    $num_list = 0;
                    $num_ne_v_nev = 0;
                    $num_v_nev = 0;
                    $num_ost_nev = 0;
                    foreach ($patients_nev as $patient)
                    {
                        if ($patient['contraindications'] == '0')
                        {
                            $num_list++;
                            $num_ne_v_nev++;
                        }
                        elseif ($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4')
                        {
                            $num_list++;
                            $num_v_nev++;
                        }
                        elseif ($patient['contraindications'] == '')
                        {
                            $num_list++;
                            $num_ost_nev++;
                        }
                    }
                    $table_nev .= '
                            <br><table class=" table-bordered table-sm mt-3">
                                <tr>
                                    <td colspan="2" align="center"><b>Осмотры Невролога</b></td>
                                </tr> 
                                <tr>
                                    <td align="left"><b>Количество проведенных осмотров всего</b></td>
                                    <td>' . $num_list . '</td>
                                </tr>
                        ';
                    if ($address_overall == '1' || $address_overall == '3')
                    {
                        $table_nev .= '
                                <tr>
                                    <td align="left"><b>Количество выявленных противопоказаний</b></td>
                                    <td>' . $num_v_nev . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '2' || $address_overall == '3')
                    {
                        $table_nev .= '
                                <tr>
                                    <td align="left"><b>Количество не выявленных противопоказаний</b></td>
                                    <td>' . $num_ne_v_nev . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '4' || $address_overall == '3')
                    {
                        $table_nev .= '
                                <tr>
                                    <td align="left"><b>Количество не проведенных осмотров</b></td>
                                    <td>' . $num_ost_nev . '</td>
                                </tr>
                            ';
                    }
                    $table_nev .= '</table>';
                }
                else
                {
                    $table_nev .= '<b>Осмотров Невролога не найдено!</b>';
                }
                if ($patients_oft != '')
                {
                    $num_list = 0;
                    $num_ne_v_oft = 0;
                    $num_v_oft = 0;
                    $num_ost_oft = 0;
                    foreach ($patients_oft as $patient)
                    {
                        if ($patient['contraindications'] == '0')
                        {
                            $num_list++;
                            $num_ne_v_oft++;
                        }
                        elseif ($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4')
                        {
                            $num_list++;
                            $num_v_oft++;
                        }
                        elseif ($patient['contraindications'] == '')
                        {
                            $num_list++;
                            $num_ost_oft++;
                        }
                    }
                    $table_oft .= '
                             <br><table class=" table-bordered table-sm mt-3">
                                <tr>
                                    <td colspan="2" align="center"><b>Осмотры Офтальмолога</b></td>
                                </tr> 
                                <tr>
                                    <td align="left"><b>Количество проведенных осмотров всего</b></td>
                                    <td>' . $num_list . '</td>
                                </tr>
                        ';
                    if ($address_overall == '1' || $address_overall == '3')
                    {
                        $table_oft .= '
                                <tr>
                                    <td align="left"><b>Количество выявленных противопоказаний</b></td>
                                    <td>' . $num_v_oft . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '2' || $address_overall == '3')
                    {
                        $table_oft .= '
                                <tr>
                                    <td align="right"><b>Количество не выявленных противопоказаний</b></td>
                                    <td>' . $num_ne_v_oft . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '4' || $address_overall == '3')
                    {
                        $table_oft .= '
                                <tr>
                                    <td align="left"><b>Количество не проведенных осмотров</b></td>
                                    <td>' . $num_ost_oft . '</td>
                                </tr>
                            ';
                    }
                    $table_oft .= '</table>';
                }
                else
                {
                    $table_oft .= '<b>Осмотров Офтальмолога не найдено!</b>';
                }
                if ($patients_oto != '')
                {
                    $num_list = 0;
                    $num_ne_v_oto = 0;
                    $num_v_oto = 0;
                    $num_ost_oto = 0;
                    foreach ($patients_oto as $patient)
                    {
                        if ($patient['contraindications'] == '0')
                        {
                            $num_list++;
                            $num_ne_v_oto++;
                        }
                        elseif ($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4')
                        {
                            $num_list++;
                            $num_v_oto++;
                        }
                        elseif ($patient['contraindications'] == '')
                        {
                            $num_list++;
                            $num_ost_oto++;
                        }
                    }
                    $table_oto .= '
                             <br><table class=" table-bordered table-sm mt-3">
                                <tr>
                                    <td colspan="2" align="center"><b>Осмотры Лора</b></td>
                                </tr> 
                                <tr>
                                    <td align="left"><b>Количество проведенных осмотров всего</b></td>
                                    <td>' . $num_list . '</td>
                                </tr>
                        ';
                    if ($address_overall == '1' || $address_overall == '3')
                    {
                        $table_oto .= '
                                <tr>
                                    <td align="left"><b>Количество выявленных противопоказаний</b></td>
                                    <td>' . $num_v_oto . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '2' || $address_overall == '3')
                    {
                        $table_oto .= '
                                <tr>
                                    <td align="right"><b>Количество не выявленных противопоказаний</b></td>
                                    <td>' . $num_ne_v_oto . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '4' || $address_overall == '3')
                    {
                        $table_oto .= '
                                <tr>
                                    <td align="left"><b>Количество не проведенных осмотров</b></td>
                                    <td>' . $num_ost_oto . '</td>
                                </tr>
                            ';
                    }
                    $table_oto .= '</table>';
                }
                else
                {
                    $table_oto .= '<b>Осмотров Лора не найдено!</b>';
                }
                if ($patients_nor != '')
                {
                    $num_list = 0;
                    $num_ne_v_nor = 0;
                    $num_v_nor = 0;
                    $num_ost_nor = 0;
                    foreach ($patients_nor as $patient)
                    {
                        if ($patient['contraindications'] == '0')
                        {
                            $num_list++;
                            $num_ne_v_nor++;
                        }
                        elseif ($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4')
                        {
                            $num_list++;
                            $num_v_nor++;
                        }
                        elseif ($patient['contraindications'] == '')
                        {
                            $num_list++;
                            $num_ost_nor++;
                        }
                    }
                    $table_nor .= '
                             <br><table class=" table-bordered table-sm mt-3">
                                <tr>
                                    <td colspan="2" align="center"><b>Осмотры Нарколога</b></td>
                                </tr> 
                                <tr>
                                    <td align="left"><b>Количество проведенных осмотров всего</b></td>
                                    <td>' . $num_list . '</td>
                                </tr>
                        ';
                    if ($address_overall == '1' || $address_overall == '3')
                    {
                        $table_nor .= '
                                <tr>
                                    <td align="left"><b>Количество выявленных противопоказаний</b></td>
                                    <td>' . $num_v_nor . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '2' || $address_overall == '3')
                    {
                        $table_nor .= '
                                <tr>
                                    <td align="right"><b>Количество не выявленных противопоказаний</b></td>
                                    <td>' . $num_ne_v_nor . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '4' || $address_overall == '3')
                    {
                        $table_nor .= '
                                <tr>
                                    <td align="left"><b>Количество не проведенных осмотров</b></td>
                                    <td>' . $num_ost_nor . '</td>
                                </tr>
                            ';
                    }
                    $table_nor .= '</table>';
                }
                else
                {
                    $table_nor .= '<b>Осмотров Нарколога не найдено!</b>';
                }
                if ($patients_ph != '')
                {
                    $num_list = 0;
                    $num_ne_v_ph = 0;
                    $num_v_ph = 0;
                    $num_ost_ph = 0;
                    foreach ($patients_ph as $patient)
                    {
                        if ($patient['contraindications'] == '0')
                        {
                            $num_list++;
                            $num_ne_v_ph++;
                        }
                        elseif ($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4')
                        {
                            $num_list++;
                            $num_v_ph++;
                        }
                        elseif ($patient['contraindications'] == '')
                        {
                            $num_list++;
                            $num_ost_ph++;
                        }
                    }
                    $table_ph .= '
                             <br><table class=" table-bordered table-sm mt-3">
                                <tr>
                                    <td colspan="2" align="center"><b>Осмотры Психиатра</b></td>
                                </tr> 
                                <tr>
                                    <td align="left"><b>Количество проведенных осмотров всего</b></td>
                                    <td>' . $num_list . '</td>
                                </tr>
                        ';
                    if ($address_overall == '1' || $address_overall == '3')
                    {
                        $table_ph .= '
                                <tr>
                                    <td align="left"><b>Количество выявленных противопоказаний</b></td>
                                    <td>' . $num_v_ph . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '2' || $address_overall == '3')
                    {
                        $table_ph .= '
                                <tr>
                                    <td align="right"><b>Количество не выявленных противопоказаний</b></td>
                                    <td>' . $num_ne_v_ph . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '4' || $address_overall == '3')
                    {
                        $table_ph .= '
                                <tr>
                                    <td align="left"><b>Количество не проведенных осмотров</b></td>
                                    <td>' . $num_ost_ph . '</td>
                                </tr>
                            ';
                    }
                    $table_ph .= '</table>';
                }
                else
                {
                    $table_ph .= '<b>Осмотров Психиатра не найдено!</b>';
                }
                if ($patients_sur != '')
                {
                    $num_list = 0;
                    $num_ne_v_sur = 0;
                    $num_v_sur = 0;
                    $num_ost_sur = 0;
                    foreach ($patients_sur as $patient)
                    {
                        if ($patient['contraindications'] == '0')
                        {
                            $num_list++;
                            $num_ne_v_sur++;
                        }
                        elseif ($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4')
                        {
                            $num_list++;
                            $num_v_sur++;
                        }
                        elseif ($patient['contraindications'] == '')
                        {
                            $num_list++;
                            $num_ost_sur++;
                        }
                    }
                    $table_sur .= '
                             <br><table class=" table-bordered table-sm mt-3">
                                <tr>
                                    <td colspan="2" align="center"><b>Осмотры Хирурга</b></td>
                                </tr> 
                                <tr>
                                    <td align="left"><b>Количество проведенных осмотров всего</b></td>
                                    <td>' . $num_list . '</td>
                                </tr>
                        ';
                    if ($address_overall == '1' || $address_overall == '3')
                    {
                        $table_sur .= '
                                <tr>
                                    <td align="left"><b>Количество выявленных противопоказаний</b></td>
                                    <td>' . $num_v_sur . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '2' || $address_overall == '3')
                    {
                        $table_sur .= '
                                <tr>
                                    <td align="right"><b>Количество не выявленных противопоказаний</b></td>
                                    <td>' . $num_ne_v_sur . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '4' || $address_overall == '3')
                    {
                        $table_sur .= '
                                <tr>
                                    <td align="left"><b>Количество не проведенных осмотров</b></td>
                                    <td>' . $num_ost_sur . '</td>
                                </tr>
                            ';
                    }
                    $table_sur .= '</table>';
                }
                else
                {
                    $table_sur .= '<b>Осмотров Хирурга не найдено!</b>';
                }
                if ($patients_stom != '')
                {
                    $num_list = 0;
                    $num_ne_v_stom = 0;
                    $num_v_stom = 0;
                    $num_ost_stom = 0;
                    foreach ($patients_stom as $patient)
                    {
                        if ($patient['contraindications'] == '0')
                        {
                            $num_list++;
                            $num_ne_v_stom++;
                        }
                        elseif ($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4')
                        {
                            $num_list++;
                            $num_v_stom++;
                        }
                        elseif ($patient['contraindications'] == '')
                        {
                            $num_list++;
                            $num_ost_stom++;
                        }
                    }
                    $table_stom .= '
                             <br><table class=" table-bordered table-sm mt-3">
                                <tr>
                                    <td colspan="2" align="center"><b>Осмотры Стоматолога</b></td>
                                </tr> 
                                <tr>
                                    <td align="left"><b>Количество проведенных осмотров всего</b></td>
                                    <td>' . $num_list . '</td>
                                </tr>
                        ';
                    if ($address_overall == '1' || $address_overall == '3')
                    {
                        $table_stom .= '
                                <tr>
                                    <td align="left"><b>Количество выявленных противопоказаний</b></td>
                                    <td>' . $num_v_stom . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '2' || $address_overall == '3')
                    {
                        $table_stom .= '
                                <tr>
                                    <td align="right"><b>Количество не выявленных противопоказаний</b></td>
                                    <td>' . $num_ne_v_stom . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '4' || $address_overall == '3')
                    {
                        $table_stom .= '
                                <tr>
                                    <td align="left"><b>Количество не проведенных осмотров</b></td>
                                    <td>' . $num_ost_stom . '</td>
                                </tr>
                            ';
                    }
                    $table_stom .= '</table>';
                }
                else
                {
                    $table_stom .= '<b>Осмотров Стоматолога не найдено!</b>';
                }
                if ($patients_der != '')
                {
                    $num_list = 0;
                    $num_ne_v_der = 0;
                    $num_v_der = 0;
                    $num_ost_der = 0;
                    foreach ($patients_der as $patient)
                    {
                        if ($patient['contraindications'] == '0')
                        {
                            $num_list++;
                            $num_ne_v_der++;
                        }
                        elseif ($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4')
                        {
                            $num_list++;
                            $num_v_der++;
                        }
                        elseif ($patient['contraindications'] == '')
                        {
                            $num_list++;
                            $num_ost_der++;
                        }
                    }
                    $table_der .= '
                             <br><table class=" table-bordered table-sm mt-3">
                                <tr>
                                    <td colspan="2" align="center"><b>Осмотры Дерматовенеролога</b></td>
                                </tr> 
                                <tr>
                                    <td align="left"><b>Количество проведенных осмотров всего</b></td>
                                    <td>' . $num_list . '</td>
                                </tr>
                        ';
                    if ($address_overall == '1' || $address_overall == '3')
                    {
                        $table_der .= '
                                <tr>
                                    <td align="left"><b>Количество выявленных противопоказаний</b></td>
                                    <td>' . $num_v_der . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '2' || $address_overall == '3')
                    {
                        $table_der .= '
                                <tr>
                                    <td align="right"><b>Количество не выявленных противопоказаний</b></td>
                                    <td>' . $num_ne_v_der . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '4' || $address_overall == '3')
                    {
                        $table_der .= '
                                <tr>
                                    <td align="left"><b>Количество не проведенных осмотров</b></td>
                                    <td>' . $num_ost_der . '</td>
                                </tr>
                            ';
                    }
                    $table_der .= '</table>';
                }
                else
                {
                    $table_der .= '<b>Осмотров Дерматовенеролога не найдено!</b>';
                }
                if ($patients_gin != '')
                {
                    $num_list = 0;
                    $num_ne_v_gin = 0;
                    $num_v_gin = 0;
                    $num_ost_gin = 0;
                    foreach ($patients_gin as $patient)
                    {
                        if ($patient['contraindications'] == '0')
                        {
                            $num_list++;
                            $num_ne_v_gin++;
                        }
                        elseif ($patient['contraindications'] == '1' || $patient['contraindications'] == '3' || $patient['contraindications'] == '4')
                        {
                            $num_list++;
                            $num_v_gin++;
                        }
                        elseif ($patient['contraindications'] == '')
                        {
                            $num_list++;
                            $num_ost_gin++;
                        }
                    }
                    $table_gin .= '
                             <br><table class=" table-bordered table-sm mt-3">
                                <tr>
                                    <td colspan="2" align="center"><b>Осмотры Гинеколога</b></td>
                                </tr> 
                                <tr>
                                    <td align="left"><b>Количество проведенных осмотров всего</b></td>
                                    <td>' . $num_list . '</td>
                                </tr>
                        ';
                    if ($address_overall == '1' || $address_overall == '3')
                    {
                        $table_gin .= '
                                <tr>
                                    <td align="left"><b>Количество выявленных противопоказаний</b></td>
                                    <td>' . $num_v_gin . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '2' || $address_overall == '3')
                    {
                        $table_gin .= '
                                <tr>
                                    <td align="right"><b>Количество не выявленных противопоказаний</b></td>
                                    <td>' . $num_ne_v_gin . '</td>
                                </tr>
                            ';
                    }
                    if ($address_overall == '4' || $address_overall == '3')
                    {
                        $table_gin .= '
                                <tr>
                                    <td align="left"><b>Количество не проведенных осмотров</b></td>
                                    <td>' . $num_ost_gin . '</td>
                                </tr>
                            ';
                    }
                    $table_gin .= '</table>';
                }
                else
                {
                    $table_gin .= '<b>Осмотров Гинеколога не найдено!</b>';
                }
            }
            ?>
            <br>
            <h5 class="text-center">Результаты проведенных осмотров <?= $doc ?></h5>

            <? /*print_r($patients)*/ ?>
            <div class="container">
                <? if ($patients_status == 2 || $patients_status == 1)
                {
                    if ($patients_ter != '')
                    {?>
                        <div class="row">
                            <div class="col-5">
                                <?if ($address_overall == '3')
                                {?>
                                    <br>
                                    <br>
                                    <br>
                                <?}?>
                                <?= $table_ter;?>
                            </div>
                            <div class="col-7"><?
                                if ($address_overall == '3')
                                {
                                    $dataPie = [
                                        'labels' => [
                                            "Выявленные противопоказания",
                                            "Не выявленные противопоказания",
                                            "Не проведенных осмотров"
                                        ],
                                        'datasets' => [
                                            [
                                                'data' => [$num_v_ter, $num_ne_v_ter, $num_ost_ter],
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
                                    echo ChartJs::widget([
                                        'type' => ChartJs::TYPE_PIE,
                                        'data' => $dataPie,
                                        'options' => []
                                    ]);
                                }
                                ?>
                            </div>
                        </div>
                   <? }
                    if ($patients_nev != '')
                    {?>
                        <br>
                        <div class="row">
                            <div class="col-5">
                                <?if ($address_overall == '3')
                                {?>
                                    <br>
                                    <br>
                                <?}?>
                                <?= $table_nev;?>
                            </div>
                            <div class="col-7"><?
                                if ($address_overall == '3')
                                {
                                    $dataPie = [
                                        'labels' => [
                                            "Выявленные противопоказания",
                                            "Не выявленные противопоказания",
                                            "Не проведенных осмотров"
                                        ],
                                        'datasets' => [
                                            [
                                                'data' => [$num_v_nev, $num_ne_v_nev, $num_ost_nev],
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
                                    echo ChartJs::widget([
                                        'type' => ChartJs::TYPE_PIE,
                                        'data' => $dataPie,
                                        'options' => []
                                    ]);
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    }
                    if ($patients_oft != '')
                    {?>
                        <br>
                        <div class="row">
                            <div class="col-5">
                                <?if ($address_overall == '3')
                                {?>
                                    <br>
                                    <br>
                                <?}?>
                                <?= $table_oft;?>
                            </div>
                            <div class="col-7"><?
                                if ($address_overall == '3')
                                {
                                    $dataPie = [
                                        'labels' => [
                                            "Выявленные противопоказания",
                                            "Не выявленные противопоказания",
                                            "Не проведенных осмотров"
                                        ],
                                        'datasets' => [
                                            [
                                                'data' => [$num_v_oft, $num_ne_v_oft, $num_ost_oft],
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
                                    echo ChartJs::widget([
                                        'type' => ChartJs::TYPE_PIE,
                                        'data' => $dataPie,
                                        'options' => []
                                    ]);
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    }
                    if ($patients_oto != '')
                    {?>
                        <br>
                        <div class="row">
                            <div class="col-5">
                                <?if ($address_overall == '3')
                                {?>
                                    <br>
                                    <br>
                                <?}?>
                                <?= $table_oto;?>
                            </div>
                            <div class="col-7"><?
                                if ($address_overall == '3')
                                {
                                    $dataPie = [
                                        'labels' => [
                                            "Выявленные противопоказания",
                                            "Не выявленные противопоказания",
                                            "Не проведенных осмотров"
                                        ],
                                        'datasets' => [
                                            [
                                                'data' => [$num_v_oto, $num_ne_v_oto, $num_ost_oto],
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
                                    echo ChartJs::widget([
                                        'type' => ChartJs::TYPE_PIE,
                                        'data' => $dataPie,
                                        'options' => []
                                    ]);
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    }
                    if ($patients_nor != '')
                    {?>
                        <br>
                        <div class="row">
                            <div class="col-5">
                                <?if ($address_overall == '3')
                                {?>
                                    <br>
                                    <br>
                                <?}?>
                                <?= $table_nor;?>
                            </div>
                            <div class="col-7"><?
                                if ($address_overall == '3')
                                {
                                    $dataPie = [
                                        'labels' => [
                                            "Выявленные противопоказания",
                                            "Не выявленные противопоказания",
                                            "Не проведенных осмотров"
                                        ],
                                        'datasets' => [
                                            [
                                                'data' => [$num_v_nor, $num_ne_v_nor, $num_ost_nor],
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
                                    echo ChartJs::widget([
                                        'type' => ChartJs::TYPE_PIE,
                                        'data' => $dataPie,
                                        'options' => []
                                    ]);
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    }
                    if ($patients_ph != '')
                    {?>
                        <br>
                        <div class="row">
                            <div class="col-5">
                                <?if ($address_overall == '3')
                                {?>
                                    <br>
                                    <br>
                                <?}?>
                                <?= $table_ph;?>
                            </div>
                            <div class="col-7"><?
                                if ($address_overall == '3')
                                {
                                    $dataPie = [
                                        'labels' => [
                                            "Выявленные противопоказания",
                                            "Не выявленные противопоказания",
                                            "Не проведенных осмотров"
                                        ],
                                        'datasets' => [
                                            [
                                                'data' => [$num_v_ph, $num_ne_v_ph, $num_ost_ph],
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
                                    echo ChartJs::widget([
                                        'type' => ChartJs::TYPE_PIE,
                                        'data' => $dataPie,
                                        'options' => []
                                    ]);
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    }
                    if ($patients_sur != '')
                    {?>
                        <br>
                        <div class="row">
                            <div class="col-5">
                                <?if ($address_overall == '3')
                                {?>
                                    <br>
                                    <br>
                                <?}?>
                                <?= $table_sur;?>
                            </div>
                            <div class="col-7"><?
                                if ($address_overall == '3')
                                {
                                    $dataPie = [
                                        'labels' => [
                                            "Выявленные противопоказания",
                                            "Не выявленные противопоказания",
                                            "Не проведенных осмотров"
                                        ],
                                        'datasets' => [
                                            [
                                                'data' => [$num_v_sur, $num_ne_v_sur, $num_ost_sur],
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
                                    echo ChartJs::widget([
                                        'type' => ChartJs::TYPE_PIE,
                                        'data' => $dataPie,
                                        'options' => []
                                    ]);
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    }
                    if ($patients_stom != '')
                    {?>
                        <br>
                        <div class="row">
                            <div class="col-5">
                                <?if ($address_overall == '3')
                                {?>
                                    <br>
                                    <br>
                                <?}?>
                                <?= $table_stom;?>
                            </div>
                            <div class="col-7"><?
                                if ($address_overall == '3')
                                {
                                    $dataPie = [
                                        'labels' => [
                                            "Выявленные противопоказания",
                                            "Не выявленные противопоказания",
                                            "Не проведенных осмотров"
                                        ],
                                        'datasets' => [
                                            [
                                                'data' => [$num_v_stom, $num_ne_v_stom, $num_ost_stom],
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
                                    echo ChartJs::widget([
                                        'type' => ChartJs::TYPE_PIE,
                                        'data' => $dataPie,
                                        'options' => []
                                    ]);
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    }
                    if ($patients_der != '')
                    {?>
                        <br>
                        <div class="row">
                            <div class="col-5">
                                <?if ($address_overall == '3')
                                {?>
                                    <br>
                                    <br>
                                <?}?>
                                <?= $table_der;?>
                            </div>
                            <div class="col-7"><?
                                if ($address_overall == '3')
                                {
                                    $dataPie = [
                                        'labels' => [
                                            "Выявленные противопоказания",
                                            "Не выявленные противопоказания",
                                            "Не проведенных осмотров"
                                        ],
                                        'datasets' => [
                                            [
                                                'data' => [$num_v_der, $num_ne_v_der, $num_ost_der],
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
                                    echo ChartJs::widget([
                                        'type' => ChartJs::TYPE_PIE,
                                        'data' => $dataPie,
                                        'options' => []
                                    ]);
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    }
                    if ($patients_gin != '')
                    { ?>
                        <br>
                        <div class="row">
                            <div class="col-5">
                                <?if ($address_overall == '3')
                                {?>
                                    <br>
                                    <br>
                                <?}?>
                                <?= $table_gin;?>
                            </div>
                            <div class="col-7"><?
                                if ($address_overall == '3')
                                {
                                    $dataPie = [
                                        'labels' => [
                                            "Выявленные противопоказания",
                                            "Не выявленные противопоказания",
                                            "Не проведенных осмотров"
                                        ],
                                        'datasets' => [
                                            [
                                                'data' => [$num_v_gin, $num_ne_v_gin, $num_ost_gin],
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
                                    echo ChartJs::widget([
                                        'type' => ChartJs::TYPE_PIE,
                                        'data' => $dataPie,
                                        'options' => []
                                    ]);
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    }
                } ?>
            </div>
        <? } ?>
        <!-- <div class="alert alert-warning" role="alert">
             Данных не найдено!
         </div>-->

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
    
    
    //графики
 
JS;
$this->registerJs($script, yii\web\View::POS_READY);