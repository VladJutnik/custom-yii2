<?php



use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
$this->title = 'Список пациентов';
$items2 = [
    '3' => 'Все',
    '1' => 'Выявлены противопоказания',
    '2' => 'Не выявлены противопоказания',
];

$list = 1;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?= $this->render('menu') ?>
        </div>
        <div class="col-9">
            <?if($po != ''){
                if(!empty($list_patients)){?>
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
                                <!--<td class="text-center"><?/*=$list_patient['id']*/?></td>-->
                                <td class="text-center"><?= Html::a('<b>Просмотр</b>', ['list-patients/view?id='.$list_patient['user_id']], [
                                        'class' => 'btn btn-sm btn-success'
                                    ])?><br><br><?= Html::a('<b>Тит. лист </b>', ['list-patients/exportk?id='.$list_patient['user_id']], [
                                        'class' => 'btn btn-sm btn-danger'
                                    ])?></td>
                                <td><?=$model->get_fio($list_patient['user_id'])?></td>
                                <td><?=$model->get_organiz($list_patient['user_id'])?></td>
                                <td><?=$model->get_profil_result($list_patient['user_id'])?></td>
                                <?/*if(
                                        Yii::$app->user->can('doctor') ||
                                        Yii::$app->user->can('doctor')
                                ){*/?><!--
                                    <td><?/*=$model->get_profil_result($list_patient['user_id'])*/?></td>
                                <?/*} else{*/?>
                                    <td>-</td>
                                --><?/*}*/?>
                            </tr>
                            <?$list++;
                        }?>
                    </table>
                <?}
                else{?>
                    <div class="alert alert-warning" role="alert">
                        Данных не найдено!
                    </div>
                <?}
            }?>
        </div>
    </div>
</div>