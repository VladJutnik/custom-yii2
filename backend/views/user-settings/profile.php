<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DirectoryActivity */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Профиль';
?>

<div class="user-profile-page">
    <div class="card radius-15">
        <div class="card-body">
            <!--Шапка профиля-->
            <div class="row">
                <div class="col-12 col-lg-7 border-right">
                    <div class="d-md-flex align-items-center">
                        <div class="mb-md-0 mb-3">
                            <img src="/image_user/<?=Yii::$app->user->identity->photo?>" class="rounded-circle shadow" width="130" height="130" alt="Ваш аватар" />
                        </div>
                        <div class="ml-md-4 flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <h4 class="mb-0"><?= Yii::$app->user->identity->name ?></h4>
                            </div>
                            <p class="text-primary"><i class='bx bx-buildings'></i> Должность</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    <table class="table table-sm table-borderless mt-md-0 mt-3">
                        <tbody>
                        <tr>
                            <th>Организация:</th>
                            <td>Название организации</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Адрес организации:</th>
                            <td>Адрес организации</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Логин:</th>
                            <td>Ваш логин</td>
                        </tr>
                        <tr>
                            <th>Ваша почта:</th>
                            <td>Ваша почта</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-content mt-3">
                <div class="card shadow-none border mb-0 radius-15">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12 col-lg-5 border-right">
                                    <?= $this->render('menu') ?>
                                </div>
                                <div class="col-12 col-lg-7">
                                    <?php $form = ActiveForm::begin() ?>

                                    <?= $form->field($model, 'name', Yii::$app->myComponent->twoColumnName())->textInput(Yii::$app->myComponent->twoColumnInput()) ?>

                                    <?= $form->field($model, 'file')->fileInput(['class' => "form-control mt-3", 'accept' => '.jpg, .png, image/jpeg, image/png'])->label(false) ?>

                                    <div class="form-group">
                                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-outline-primary mt-3 px-5 radius-30 btn-block']) ?>
                                    </div>
                                    <?php ActiveForm::end() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
