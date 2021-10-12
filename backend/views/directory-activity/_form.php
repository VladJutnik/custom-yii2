<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DirectoryActivity */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="directory-activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name', Yii::$app->myComponent->twoColumnName())->textInput(Yii::$app->myComponent->twoColumnInput()) ?>

    <?= $form->field($model, 'status_view', Yii::$app->myComponent->twoColumnName())->dropDownList(Yii::$app->myComponent->statusView(), Yii::$app->myComponent->twoColumnInput()) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-outline-primary mt-3 px-5 radius-30 btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
