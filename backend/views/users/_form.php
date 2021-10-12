<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['value'=> $model->name]); ?>
            
            <?= $form->field($model, 'phone')->widget(MaskedInput::className(),['mask'=>'+7-(999)-999-99-99','clientOptions' => ['removeMaskOnSubmit' => true]])->textInput(['placeholder'=>'+7-(999)-999-99-99']);?>

            <?= $form->field($model, 'email')->textInput(['value'=> $model->email]); ?>
            
            <?= $form->field($model, 'password_hash')->textInput()->label('Пароль' . Html::tag('span', ' ?', ['title'=>'Если хотите сменить введите новый, чтобы оставить прежний оставьте пустым','data-toggle'=>'tooltip','style'=>'text-decoration: underline; cursor:pointer;' ])); ?>
            
            <? $roles = []; ?>

            <?// if(Yii::$app->user->identity->ugroup == 'admin'){?>
                <?/* $roles = [
                    'user' => 'Клиент',
                ]; */?>
            <?// } ?>

            <?//= $form->field($model, 'role')->dropDownList($roles)->label('Роль');?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
