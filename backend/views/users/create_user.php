<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Регистрация сотрудников';
$post_items = [
        1 => 'Медицинский работник',
        2 => 'Работник столовой',
        3 => 'Учитель/Классный руководитель',
];
?>
<div class="user-create">
    <div class="row justify-content-center mt-3">
        <div class="col-md-6">
                <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model2, 'name')->textInput(); ?>

                <?= $form->field($model2, 'post')->dropDownList($post_items) ?>

                <?= $form->field($model2, 'email')->textInput()->label('Email регистрируемого сотрудника'); ?>

                <?= $form->field($model2, 'password')->textInput(); ?>
                <div class="form-group">
                    <?= Html::submitButton('Зарегистрировать', ['class' => 'btn main-button-3 col-md-12']) ?>
                </div>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?
$script = <<< JS

    
JS;

$this->registerJs($script, yii\web\View::POS_READY);

/*<script>

    //function ChangeColor() {
    //    alert(document.getElementById("txt").value);
    //    console.log($('#txt').val());
    //}
    //document.getElementById("btn").onclick = someFunc;
</script>*/