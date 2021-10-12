<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Редактирование заявки №: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['value'=> $model->name]); ?>

            <?= $form->field($model, 'phone')->widget(MaskedInput::className(),['mask'=>'+7-(999)-999-99-99','clientOptions' => ['removeMaskOnSubmit' => true]])->textInput(['placeholder'=>'+7-(999)-999-99-99']);?>

            <?= $form->field($model, 'email')->textInput(['value'=> $model->email]); ?>

            <?= $form->field($model, 'post')->textInput(['value'=> $model->post]); ?>



            <?php
            echo '<div class="row mt-3 field-organization-short_title">
                <label class="col-11 col-md-4 col-form-label font-weight-bold">Страна:</label>
                <input type="text" class="form-control col-11 col-md-5" value="Россия" readonly="true">
                <div class="invalid-feedback"></div>
              </div>';
            echo '<div class="row mt-3 field-organization-short_title">
                <label class="col-11 col-md-4 col-form-label font-weight-bold">Федеральный округ:</label>
                <input type="text" class="form-control col-11 col-md-5" value="' . $model_organization->get_district($my_organization->federal_district_id) . '" readonly="true">
                <div class="invalid-feedback"></div>
              </div>';
            echo '<div class="row mt-3 field-organization-short_title">
                <label class="col-11 col-md-4 col-form-label font-weight-bold">Субъект федерации:</label>
                <input type="text" class="form-control col-11 col-md-5" value="' . $model_organization->get_region($my_organization->region_id) . '" readonly="true">
                <div class="invalid-feedback"></div>
              </div>';
            echo '<div class="row mt-3 field-organization-short_title">
                <label class="col-11 col-md-4 col-form-label font-weight-bold">Муниципальное образование:</label>
                <input type="text" class="form-control col-11 col-md-5" value="' . $model_organization->get_municipality($my_organization->municipality_id) . '" readonly="true">
                <div class="invalid-feedback"></div>
              </div>';
            echo '<div class="row mt-3 field-organization-short_title">
                <label class="col-11 col-md-4 col-form-label font-weight-bold">Название организации:</label>
                <input type="text" class="form-control col-11 col-md-5" value="' . $my_organization->title . '" readonly="true">
                <div class="invalid-feedback"></div>
              </div>';

            ?>



            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

</div>
