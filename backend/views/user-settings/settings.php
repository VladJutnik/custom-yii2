<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DirectoryActivity */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Настройки профиля';
?>
<div class="user-profile-page">
    <div class="card radius-15">
        <div class="card-body">
            <div class="tab-content mt-3">
                <div class="card shadow-none border mb-0 radius-15">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12 col-lg-5 border-right">
                                    <?= $this->render('menu') ?>
                                </div>
                                <div class="col-12 col-lg-7">
                                    <div class="card-title">
                                        <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
                                    </div>
                                    <hr/>
                                    <?php $form = ActiveForm::begin(); ?>
                                    <!--user_id убрать так как это будет делаться автоматически при регистрации-->
                                    <?= $form->field($model, 'user_id')->hiddenInput(['maxlength' => true, 'class' => 'form-control col-6', 'value' => Yii::$app->user->identity->id])->label(false) ?>
                                    <? $items = [
                                        '' => 'светлая тема',
                                        'dark-theme' => 'темная тема',
                                    ]; ?>
                                    <?= $form->field($model, 'topic', Yii::$app->myComponent->twoColumnName())->dropDownList($items, Yii::$app->myComponent->twoColumnInput()) ?>
                                    <? $items = [
                                        '' => 'всегда светлый',
                                        'dark-sidebar' => 'всегда темный',
                                    ]; ?>
                                    <?= $form->field($model, 'dark_slider', Yii::$app->myComponent->twoColumnName())->dropDownList($items, Yii::$app->myComponent->twoColumnInput()) ?>
                                    <? $items = [
                                        '' => 'всегда светлые',
                                        'ColorLessIcons' => 'всегда темные',
                                    ]; ?>
                                    <?= $form->field($model, 'dark_icons', Yii::$app->myComponent->twoColumnName())->dropDownList($items, Yii::$app->myComponent->twoColumnInput()) ?>

                                    <div class="form-group">
                                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-outline-primary mt-3 px-5 radius-30 btn-block']) ?>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>