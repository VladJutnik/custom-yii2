<?php


use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
$this->title = 'Список пациентов';
//print_r(Yii::$app->user->identity->post);
//print_r('<br>');
//print_r(Yii::$app->user->can('admin'));
$list = 1;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?= $this->render('menu') ?>
        </div>
        <div class="col-9">
            <div class="text-center"><h5>Список пациентов на <?=$today?></h5></div>
            <table class="table table-bordered table-sm mt-3">
                <tr>
                    <td class="text-center"><b>№</b></td>
                    <!--<td class="text-center"><b>Номер</b></td>-->
                    <td class="text-center"><b>Посмотреть</b></td>
                    <td class="text-center"><b>ФИО</b></td>
                    <td class="text-center"><b>Организация</b></td>
                    <td class="text-center"><b>Результаты осмотров</b></td>

                </tr>
                <?foreach ($list_patients as $list_patient){?>
                    <tr>
                        <td class="text-center"><?=$list?></td>
                        <!--<td class="text-center"><?/*/*=$list_patient['id']*/?></td>-->
                        <td class="text-center"><?= Html::a('<b>Просмотр</b>', ['list-patients/view?id='.$list_patient['user_id']], [
                                'class' => 'btn btn-sm btn-outline-success'
                            ])?><br><br><?= Html::a('<b>Тит. лист </b>', ['list-patients/exportk?id='.$list_patient['user_id']], [
                                'class' => 'btn btn-sm btn-outline-danger'
                            ])?></td>
                        <td><?=$model->get_fio($list_patient['user_id'])?></td>
                        <td><?=$model->get_organiz($list_patient['user_id'])?></td>
                        <td><?=$model->get_profil_result($list_patient['user_id'])?></td>
                    </tr>
                    <?$list++;
                }?>
            </table>
        </div>
    </div>
</div>
