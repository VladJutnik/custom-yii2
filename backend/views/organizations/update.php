<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Organization */

$this->title = 'Редактировать организацию: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Организации', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="organization-update">

    <div class="organization-form">
        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
        <?php $form = ActiveForm::begin(); ?>
        <?php
        $two_column = ['options' => ['class' => 'row justify-content-center mt-3'], 'labelOptions' => ['class' => 'col-6 col-md-3 col-form-label font-weight-bold']];
        $param1 = ['class' => 'form-control col-11 col-md-4'];
        ?>
        <?= $form->field($model, 'title', $two_column)->textInput(['maxlength' => true, 'class' => 'form-control col-11 col-md-4']) ?>

        <?= $form->field($model, 'address', $two_column)->textInput(['maxlength' => true, 'class' => 'form-control col-11 col-md-4']) ?>

        <?= $form->field($model, 'inn', $two_column)->textInput(['maxlength' => true, 'class' => 'form-control col-11 col-md-4'])->label('ОГРН') ?>

        <?= $form->field($model, 'org_balansodergatel',$two_column)->textInput(['maxlength' => true, 'class' => 'form-control col-11 col-md-4'])->label('ОКВЭД') ?>
        <?= $form->field($model, 'position_commissioner',$two_column)->textInput(['maxlength' => true, 'class' => 'form-control col-11 col-md-4'])->label('ОКВЭД') ?>
        <?= $form->field($model, 'fio_position_commissioner',$two_column)->textInput(['maxlength' => true, 'class' => 'form-control col-11 col-md-4'])->label('ОКВЭД') ?>
        <?= $form->field($model, 'VK_chairman',$two_column)->textInput(['maxlength' => true, 'class' => 'form-control col-11 col-md-4'])->label('ОКВЭД') ?>
        <?= $form->field($model, 'VK_chairman_position',$two_column)->textInput(['maxlength' => true, 'class' => 'form-control col-11 col-md-4'])->label('ОКВЭД') ?>
        <?= $form->field($model, 'contract_number',$two_column)->textInput(['maxlength' => true, 'class' => 'form-control col-11 col-md-4'])->label('ОКВЭД') ?>


        <div class="form-group">
            <?= Html::submitButton('Сохранение', ['class' => 'btn btn-success form-control mt-3']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
